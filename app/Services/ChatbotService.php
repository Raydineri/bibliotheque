<?php
namespace App\Services;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use App\Models\ChatHistory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotService
{
    private string $apiKey;
    private string $model;
    private string $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
        $rawModel     = config('services.gemini.model', 'gemini-2.0-flash');
        $this->model  = $this->normalizeModelName($rawModel);
        $baseUrl      = config('services.gemini.base_url', 'https://generativelanguage.googleapis.com/v1beta');
        $this->apiUrl = $this->buildApiUrl($this->model, $baseUrl);
    }

    private function normalizeModelName(string $model): string
    {
        // Migre automatiquement les anciens modèles Gemini 1.x (retirés par Google)
        // vers un modèle 2.x toujours pris en charge par generateContent.
        if (preg_match('/^gemini-1\.(0|5)/', $model)) {
            return 'gemini-2.0-flash';
        }

        return $model;
    }

    private function buildApiUrl(string $model, string $baseUrl): string
    {
        return rtrim($baseUrl, '/') . "/models/{$model}:generateContent";
    }

    private function getFallbackApiUrls(): array
    {
        $baseUrls = [
            config('services.gemini.base_url', 'https://generativelanguage.googleapis.com/v1beta'),
            'https://generativelanguage.googleapis.com/v1',
        ];

        $models = [
            $this->model,
            'gemini-2.0-flash',
            'gemini-2.5-flash',
            'gemini-2.0-flash-lite',
        ];

        $urls = [];
        foreach ($baseUrls as $baseUrl) {
            foreach ($models as $model) {
                $url = $this->buildApiUrl($model, $baseUrl);
                if (!in_array($url, $urls, true)) {
                    $urls[] = $url;
                }
            }
        }

        return $urls;
    }

    private function postGeminiRequest(string $apiUrl, array $contents): \Illuminate\Http\Client\Response
    {
        return Http::timeout(30)->post("{$apiUrl}?key={$this->apiKey}", [
            'contents'         => $contents,
            'generationConfig' => [
                'temperature'     => 0.7,
                'maxOutputTokens' => 1024,
            ],
        ]);
    }

    /**
     * Point d'entrée principal : traite la question de l'utilisateur
     */
    public function ask(User $user, string $question): string
    {
        // Étape 1 : Récupérer les données métier pertinentes
        $contextData = $this->gatherRelevantData($user, $question);

        // Étape 2 : Construire le prompt intelligent
        $systemPrompt = $this->buildSystemPrompt($user, $contextData);

        // Étape 3 : Récupérer l'historique de la conversation
        $history = $this->getFormattedHistory($user);

        // Étape 4 : Appeler l'API Gemini
        $reply = $this->callGeminiApi($systemPrompt, $history, $question);

        // Étape 5 : Sauvegarder en base
        $this->saveMessage($user, 'user', $question);
        $this->saveMessage($user, 'model', $reply);

        return $reply;
    }

    /**
     * Récupère les données métier depuis la base selon le contexte de la question
     */
    private function gatherRelevantData(User $user, string $question): array
    {
        $data = [];
        $q    = mb_strtolower($question);

        // Livres disponibles
        if (str_contains($q, 'disponible') || str_contains($q, 'livre') || str_contains($q, 'catalogue')) {
            $data['available_books'] = Book::with(['author', 'category'])
                ->available()->limit(10)->get()
                ->map(fn($b) => [
                    'id'        => $b->id,
                    'titre'     => $b->title,
                    'auteur'    => $b->author->name,
                    'catégorie' => $b->category->name,
                    'copies_dispo' => $b->available_copies,
                ])->toArray();
        }

        // Emprunts de l'utilisateur
        if (str_contains($q, 'emprunt') || str_contains($q, 'mes livre') || str_contains($q, 'retour')) {
            $data['my_loans'] = Loan::with('book.author')
                ->where('user_id', $user->id)
                ->where('status', 'active')
                ->get()
                ->map(fn($l) => [
                    'livre'       => $l->book->title,
                    'auteur'      => $l->book->author->name,
                    'emprunté_le' => $l->borrowed_at->format('d/m/Y'),
                    'retour_avant'=> $l->due_date->format('d/m/Y'),
                    'en_retard'   => $l->due_date->isPast() ? 'OUI' : 'non',
                ])->toArray();
        }

        // Statistiques (admin ou tous)
        if (str_contains($q, 'statistique') || str_contains($q, 'plus emprunté') || str_contains($q, 'populaire')) {
            $data['stats'] = [
                'total_livres'     => Book::count(),
                'livres_dispo'     => Book::available()->count(),
                'emprunts_actifs'  => Loan::where('status', 'active')->count(),
                'emprunts_retard'  => Loan::overdue()->count(),
            ];
            $data['most_borrowed'] = Book::withCount('loans')
                ->orderByDesc('loans_count')
                ->limit(5)->get()
                ->map(fn($b) => ['titre' => $b->title, 'nb_emprunts' => $b->loans_count])
                ->toArray();
        }

        // Recherche par titre/auteur
        if (str_contains($q, 'trouv') || str_contains($q, 'cherch') || str_contains($q, 'existe')) {
            // Extraire les mots-clés significatifs
            preg_match('/"([^"]+)"|(?:livre|auteur|titre)\s+(\w+)/ui', $question, $matches);
            $keyword = $matches[1] ?? $matches[2] ?? null;
            if ($keyword) {
                $data['search_results'] = Book::with(['author', 'category'])
                    ->where('title', 'ilike', "%{$keyword}%")
                    ->orWhereHas('author', fn($q) => $q->where('name', 'ilike', "%{$keyword}%"))
                    ->get()
                    ->map(fn($b) => [
                        'titre'     => $b->title,
                        'auteur'    => $b->author->name,
                        'disponible'=> $b->available_copies > 0 ? 'oui' : 'non',
                    ])->toArray();
            }
        }

        return $data;
    }

    /**
     * Construit le prompt système avec le contexte métier
     */
    private function buildSystemPrompt(User $user, array $contextData): string
    {
        $dataJson = empty($contextData)
            ? "Aucune donnée spécifique récupérée pour cette question."
            : json_encode($contextData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        return <<<PROMPT
Tu es BiblioBot, l'assistant intelligent de la Bibliothèque Numérique.
Tu aides les membres à gérer leurs emprunts, trouver des livres et obtenir des informations.

UTILISATEUR CONNECTÉ :
- Nom : {$user->name}
- Email : {$user->email}
- Rôle : {$user->getRoleNames()->first()}

DONNÉES DE LA BIBLIOTHÈQUE (récupérées en temps réel depuis la base de données) :
{$dataJson}

RÈGLES IMPORTANTES :
1. Réponds UNIQUEMENT en français.
2. Base tes réponses EXCLUSIVEMENT sur les données fournies ci-dessus.
3. Si une information n'est pas dans les données, dis-le clairement.
4. Sois concis, précis et utile.
5. Pour les dates de retour dépassées, signale-le clairement à l'utilisateur.
6. Ne mens jamais sur les données — si disponible_copies = 0, le livre n'est pas disponible.
PROMPT;
    }

    /**
     * Récupère et formate l'historique pour l'API Gemini
     */
    private function getFormattedHistory(User $user): array
    {
        return ChatHistory::where('user_id', $user->id)
            ->orderBy('created_at')
            ->take(10) // Garder les 10 derniers messages
            ->get()
            ->map(fn($msg) => [
                'role'  => $msg->role,
                'parts' => [['text' => $msg->content]],
            ])->toArray();
    }

    /**
     * Appel HTTP à l'API Google Gemini
     */
    private function callGeminiApi(string $systemPrompt, array $history, string $question): string
    {
        try {
            $contents = array_merge([
                ['role' => 'user', 'parts' => [[
                    'text' => "[SYSTEM]\n" . $systemPrompt,
                ]]],
            ], $history, [
                ['role' => 'user', 'parts' => [['text' => $question]]]
            ]);

            $response = $this->postGeminiRequest($this->apiUrl, $contents);

            if ($response->failed() && $response->status() === 404) {
                foreach ($this->getFallbackApiUrls() as $fallbackUrl) {
                    if ($fallbackUrl === $this->apiUrl) {
                        continue;
                    }

                    $retry = $this->postGeminiRequest($fallbackUrl, $contents);
                    if (!$retry->failed()) {
                        $response = $retry;
                        break;
                    }
                }
            }

            if ($response->failed()) {
                Log::error('Gemini API Error', [
                    'status'   => $response->status(),
                    'response' => $response->body(),
                ]);

                // 429 = quota / rate limit dépassé (souvent le quota gratuit journalier).
                if ($response->status() === 429) {
                    return "Le quota d'utilisation de l'assistant IA est atteint pour le moment. "
                        . "Merci de réessayer dans quelques minutes.";
                }

                return "Désolé, le service IA est temporairement indisponible. Veuillez réessayer.";
            }

            return $response->json('candidates.0.content.parts.0.text')
                ?? "Je n'ai pas pu générer une réponse. Veuillez reformuler votre question.";

        } catch (\Exception $e) {
            Log::error('Gemini Exception', ['message' => $e->getMessage()]);
            return "Une erreur est survenue. Veuillez vérifier votre connexion et réessayer.";
        }
    }

    private function saveMessage(User $user, string $role, string $content): void
    {
        ChatHistory::create([
            'user_id' => $user->id,
            'role'    => $role,
            'content' => $content,
        ]);
    }
}

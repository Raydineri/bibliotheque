<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">🤖 BiblioBot — Assistant IA</h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto px-4">
        {{-- Fenêtre de chat --}}
        <div class="bg-white rounded-2xl shadow-lg flex flex-col h-[600px]">

            {{-- Messages --}}
            <div id="chat-messages" class="flex-1 overflow-y-auto p-6 space-y-4">
                {{-- Message de bienvenue --}}
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm">🤖</div>
                    <div class="bg-gray-100 rounded-2xl rounded-tl-none px-4 py-3 max-w-md">
                        <p class="text-sm text-gray-800">Bonjour {{ auth()->user()->name }} ! Je suis BiblioBot. Posez-moi vos questions sur la bibliothèque : livres disponibles, vos emprunts, statistiques...</p>
                    </div>
                </div>

                {{-- Historique --}}
                @foreach($history as $msg)
                    @if($msg->role === 'user')
                        <div class="flex items-start gap-3 flex-row-reverse">
                            <div class="w-9 h-9 bg-green-600 rounded-full flex items-center justify-center text-white text-sm">👤</div>
                            <div class="bg-green-600 text-white rounded-2xl rounded-tr-none px-4 py-3 max-w-md">
                                <p class="text-sm">{{ $msg->content }}</p>
                            </div>
                        </div>
                    @else
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm">🤖</div>
                            <div class="bg-gray-100 rounded-2xl rounded-tl-none px-4 py-3 max-w-md">
                                <p class="text-sm text-gray-800 whitespace-pre-wrap">{{ $msg->content }}</p>
                            </div>
                        </div>
                    @endif
                @endforeach

                {{-- Indicateur de frappe --}}
                <div id="typing-indicator" class="hidden flex items-start gap-3">
                    <div class="w-9 h-9 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm">🤖</div>
                    <div class="bg-gray-100 rounded-2xl rounded-tl-none px-4 py-3">
                        <div class="flex gap-1">
                            <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></span>
                            <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay:.1s"></span>
                            <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay:.2s"></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Zone de saisie --}}
            <div class="border-t p-4">
                <div class="flex gap-3">
                    <input
                        id="chat-input"
                        type="text"
                        placeholder="Ex: Quels livres sont disponibles ? Mes emprunts en retard ?"
                        class="flex-1 border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                    <button
                        id="send-btn"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl text-sm font-medium transition"
                    >
                        Envoyer
                    </button>
                </div>
                {{-- Suggestions rapides --}}
                <div class="flex flex-wrap gap-2 mt-3">
                    @foreach([
                        'Livres disponibles',
                        'Mes emprunts actifs',
                        'Livres les plus empruntés',
                        'Ai-je des retards ?'
                    ] as $suggestion)
                        <button
                            class="suggestion-btn text-xs bg-blue-50 text-blue-600 border border-blue-200 px-3 py-1 rounded-full hover:bg-blue-100 transition"
                        >{{ $suggestion }}</button>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Bouton effacer historique --}}
        <div class="mt-3 text-right">
            <button id="clear-btn" class="text-xs text-red-400 hover:text-red-600">
                🗑 Effacer l'historique
            </button>
        </div>
    </div>

    <script>
        const messagesDiv  = document.getElementById('chat-messages');
        const input        = document.getElementById('chat-input');
        const sendBtn      = document.getElementById('send-btn');
        const typingDiv    = document.getElementById('typing-indicator');
        const csrfToken    = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function scrollToBottom() {
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }

        function appendMessage(role, text) {
            const isUser = role === 'user';
            const html = isUser
                ? `<div class="flex items-start gap-3 flex-row-reverse">
                <div class="w-9 h-9 bg-green-600 rounded-full flex items-center justify-center text-white text-sm">👤</div>
                <div class="bg-green-600 text-white rounded-2xl rounded-tr-none px-4 py-3 max-w-md">
                    <p class="text-sm">${escHtml(text)}</p>
                </div>
               </div>`
                : `<div class="flex items-start gap-3">
                <div class="w-9 h-9 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm">🤖</div>
                <div class="bg-gray-100 rounded-2xl rounded-tl-none px-4 py-3 max-w-md">
                    <p class="text-sm text-gray-800 whitespace-pre-wrap">${escHtml(text)}</p>
                </div>
               </div>`;
            typingDiv.insertAdjacentHTML('beforebegin', html);
            scrollToBottom();
        }

        function escHtml(str) {
            return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
        }

        async function sendMessage(message) {
            if (!message.trim()) return;
            input.value = '';
            sendBtn.disabled = true;
            appendMessage('user', message);
            typingDiv.classList.remove('hidden');
            scrollToBottom();

            try {
                const res = await fetch('/api/chatbot', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ message })
                });
                const data = await res.json();
                typingDiv.classList.add('hidden');
                appendMessage('model', data.reply || "Erreur inattendue.");
            } catch (e) {
                typingDiv.classList.add('hidden');
                appendMessage('model', "Erreur de connexion. Réessayez.");
            }
            sendBtn.disabled = false;
            input.focus();
        }

        sendBtn.addEventListener('click', () => sendMessage(input.value));
        input.addEventListener('keydown', e => { if (e.key === 'Enter') sendMessage(input.value); });

        document.querySelectorAll('.suggestion-btn').forEach(btn => {
            btn.addEventListener('click', () => sendMessage(btn.textContent.trim()));
        });

        document.getElementById('clear-btn').addEventListener('click', async () => {
            const result = await window.Swal.fire({
                title: 'Effacer tout l\'historique ? ',
                text: 'Cette action est irreversible.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Effacer',
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#e11d48'
            });

            if (!result.isConfirmed) {
                return;
            }

            await fetch('/api/chatbot/history', {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken }
            });
            location.reload();
        });

        scrollToBottom();
    </script>
</x-app-layout>

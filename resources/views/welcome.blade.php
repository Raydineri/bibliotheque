<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'BiblioTech') }} | Accueil</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900">
<div class="min-h-screen">
    <header class="border-b border-slate-200 bg-white/90 backdrop-blur">
        <div class="mx-auto flex w-full max-w-6xl items-center justify-between px-6 py-4">
            <a href="/" class="flex items-center gap-3">
                <x-application-logo class="h-10 w-10" />
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-[0.2em]">Bibliotheque</p>
                    <p class="text-lg font-semibold">BiblioTech</p>
                </div>
            </a>

            @if (Route::has('login'))
                <div class="flex items-center gap-2">
                    <a class="app-btn app-btn-ghost" href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Se connecter</span>
                    </a>
                    @if (Route::has('register'))
                        <a class="app-btn app-btn-primary" href="{{ route('register') }}">
                            <i class="fas fa-user-plus"></i>
                            <span>Creer un compte</span>
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </header>

    <main>
        <section class="mx-auto w-full max-w-6xl px-6 py-16">
            <div class="grid items-center gap-12 lg:grid-cols-2">
                <div>
                    <span class="app-badge app-badge-info">Bienvenue sur BiblioTech</span>
                    <h1 class="mt-4 text-4xl font-semibold leading-tight text-slate-900">
                        La bibliotheque numerique moderne pour gerer vos livres et vos emprunts.
                    </h1>
                    <p class="mt-4 text-base text-slate-600">
                        Centralisez votre catalogue, suivez les emprunts et offrez une experience fluide
                        a votre equipe avec une interface claire et un assistant intelligent.
                    </p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a class="app-btn app-btn-primary" href="{{ route('login') }}">
                            <i class="fas fa-arrow-right"></i>
                            <span>Acceder a l'app</span>
                        </a>
                        @if (Route::has('register'))
                            <a class="app-btn app-btn-secondary" href="{{ route('register') }}">
                                <i class="fas fa-user-shield"></i>
                                <span>Demarrer</span>
                            </a>
                        @endif
                    </div>
                    <div class="mt-8 grid gap-4 sm:grid-cols-3">
                        <div class="app-card-muted p-4">
                            <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Catalogue</p>
                            <p class="mt-2 text-sm text-slate-600">Livres, auteurs et categories en un seul endroit.</p>
                        </div>
                        <div class="app-card-muted p-4">
                            <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Emprunts</p>
                            <p class="mt-2 text-sm text-slate-600">Statuts clairs, rappels et historique.</p>
                        </div>
                        <div class="app-card-muted p-4">
                            <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Assistant IA</p>
                            <p class="mt-2 text-sm text-slate-600">Aide instantanee et recommandations.</p>
                        </div>
                    </div>
                </div>
                <div class="app-card p-6">
                    <div class="grid gap-4">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600">
                                <i class="fas fa-book"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold">Catalogue intelligent</h3>
                                <p class="text-sm text-slate-600">Recherche rapide, filtres clairs et fiches detaillees.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                                <i class="fas fa-hand-holding-heart"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold">Suivi des emprunts</h3>
                                <p class="text-sm text-slate-600">Alertes, historique et statut en temps reel.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                                <i class="fas fa-robot"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold">Assistant IA</h3>
                                <p class="text-sm text-slate-600">Recommandations et aide instantanee.</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 rounded-xl border border-dashed border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Flux de travail</p>
                        <p class="mt-2 text-sm text-slate-600">
                            Importez vos livres, attribuez les emprunts et gardez une vision globale avec des
                            indicateurs simples.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="border-t border-slate-200 bg-white">
            <div class="mx-auto w-full max-w-6xl px-6 py-12">
                <div class="grid gap-6 md:grid-cols-3">
                    <div class="app-card p-6">
                        <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Experience</p>
                        <h3 class="mt-3 text-xl font-semibold">Interface coherente</h3>
                        <p class="mt-2 text-sm text-slate-600">Des composants uniformes pour chaque fonctionnalite.</p>
                    </div>
                    <div class="app-card p-6">
                        <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Performance</p>
                        <h3 class="mt-3 text-xl font-semibold">Navigation rapide</h3>
                        <p class="mt-2 text-sm text-slate-600">Des pages optimisees pour un usage quotidien.</p>
                    </div>
                    <div class="app-card p-6">
                        <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Collaboration</p>
                        <h3 class="mt-3 text-xl font-semibold">Gestion d'equipe</h3>
                        <p class="mt-2 text-sm text-slate-600">Roles clairs pour administrateurs et membres.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="border-t border-slate-200 bg-slate-50">
        <div class="mx-auto flex w-full max-w-6xl items-center justify-between px-6 py-6 text-sm text-slate-500">
            <p>{{ config('app.name', 'BiblioTech') }} - Bibliotheque numerique</p>
            <p>Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})</p>
        </div>
    </footer>
</div>
</body>
</html>

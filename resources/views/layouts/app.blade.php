<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Bibliothèque') }} - {{ $title ?? '' }}</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-slate-50 text-slate-900">
<div x-data="{ sidebarOpen: false }" class="min-h-screen bg-slate-50">

    {{-- Mobile overlay --}}
    <div x-show="sidebarOpen" x-transition.opacity
         class="fixed inset-0 z-30 bg-slate-900/40 lg:hidden"
         @click="sidebarOpen = false"></div>

    {{-- ===== SIDEBAR ===== --}}
    <aside
        class="app-sidebar fixed inset-y-0 left-0 z-40 w-72 text-white flex flex-col transform transition duration-200 lg:translate-x-0"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        {{-- Logo --}}
        <div class="p-6 border-b border-white/10">
            <div class="flex items-center gap-3">
                <x-application-logo class="w-10 h-10" />
                <div>
                    <h1 class="font-semibold text-lg leading-tight">BiblioTech</h1>
                    <p class="text-xs text-slate-300">Bibliothèque Numérique</p>
                </div>
            </div>
        </div>

        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">

            <a href="{{ route('dashboard') }}"
               class="app-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt w-5 text-center"></i>
                <span>Tableau de bord</span>
            </a>

            {{-- ===== SECTION CATALOGUE ===== --}}
            <div class="pt-4 pb-1 px-4 text-[11px] text-slate-400 uppercase tracking-widest font-semibold">Catalogue</div>

            <a href="{{ route('books.index') }}"
               class="app-nav-link {{ request()->routeIs('books.*') ? 'active' : '' }}">
                <i class="fas fa-book w-5 text-center"></i>
                <span>Livres</span>
            </a>

            @role('admin')
            <a href="{{ route('admin.authors.index') }}"
               class="app-nav-link {{ request()->routeIs('admin.authors.*') ? 'active' : '' }}">
                <i class="fas fa-user-pen w-5 text-center"></i>
                <span>Auteurs</span>
            </a>

            <a href="{{ route('admin.categories.index') }}"
               class="app-nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="fas fa-tags w-5 text-center"></i>
                <span>Catégories</span>
            </a>
            @endrole

            {{-- ===== SECTION GESTION ===== --}}
            <div class="pt-4 pb-1 px-4 text-[11px] text-slate-400 uppercase tracking-widest font-semibold">Gestion</div>

            <a href="{{ route('loans.index') }}"
               class="app-nav-link {{ request()->routeIs('loans.*') ? 'active' : '' }}">
                <i class="fas fa-hand-holding-heart w-5 text-center"></i>
                <span>{{ auth()->user()->hasRole('admin') ? 'Tous les emprunts' : 'Mes emprunts' }}</span>
            </a>

            @role('admin')
            {{-- ===== SECTION ADMIN ===== --}}
            <div class="pt-4 pb-1 px-4 text-[11px] text-slate-400 uppercase tracking-widest font-semibold">Administration</div>

            <a href="{{ route('admin.users.index') }}"
               class="app-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users-cog w-5 text-center"></i>
                <span>Utilisateurs</span>
            </a>
            @endrole

            {{-- ===== SECTION IA ===== --}}
            <div class="pt-4 pb-1 px-4 text-[11px] text-slate-400 uppercase tracking-widest font-semibold">Assistant IA</div>

            <a href="{{ route('chatbot.index') }}"
               class="app-nav-link {{ request()->routeIs('chatbot.*') ? 'active' : '' }}">
                <i class="fas fa-robot w-5 text-center"></i>
                <span>BiblioBot</span>
                <span class="ml-auto app-badge app-badge-success">IA</span>
            </a>

            @role('user')
            {{-- ===== SECTION MEMBRE ===== --}}
            <div class="pt-4 pb-1 px-4 text-[11px] text-slate-400 uppercase tracking-widest font-semibold">Mon Compte</div>

            <a href="{{ route('profile.show') }}"
               class="app-nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <i class="fas fa-user-circle w-5 text-center"></i>
                <span>Mon Profil</span>
            </a>
            @endrole

        </nav>

        {{-- User info --}}
        <div class="p-4 border-t border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-white/10 rounded-full flex items-center justify-center">
                    <span class="text-sm font-semibold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-300">{{ auth()->user()->getRoleNames()->first() ?? 'membre' }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Déconnexion"
                            class="text-slate-300 hover:text-white transition">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ===== CONTENU PRINCIPAL ===== --}}
    <main class="lg:ml-72 min-h-screen">

        {{-- Topbar --}}
        <header class="sticky top-0 z-20 bg-white/80 backdrop-blur border-b border-slate-200">
            <div class="px-6 lg:px-8 py-4 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <button type="button" class="lg:hidden text-slate-600 hover:text-slate-900"
                            @click="sidebarOpen = true" aria-label="Ouvrir le menu">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-widest">Administration</p>
                        <h2 class="text-xl font-semibold text-slate-900">
                            {{ $header ?? 'Tableau de bord' }}
                        </h2>
                    </div>
                </div>
                <div class="flex items-center gap-3 lg:gap-4">
                    <div class="hidden md:flex items-center gap-2 text-sm text-slate-500">
                        <i class="fas fa-calendar-alt"></i>
                        <span>{{ now()->format('d/m/Y') }}</span>
                    </div>
                    @if(auth()->user()->hasRole('admin'))
                        <span class="app-badge app-badge-info">
                            <i class="fas fa-crown mr-1"></i>Admin
                        </span>
                    @endif
                </div>
            </div>
        </header>

        {{-- Alertes --}}
        <div class="px-6 lg:px-8 pt-4">
            @if(session('success'))
                <div class="app-alert app-alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="app-alert app-alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                </div>
            @endif
        </div>

        {{-- Page Content --}}
        <div class="p-6 lg:p-8">
            {{ $slot }}
        </div>
    </main>
</div>
</body>
</html>

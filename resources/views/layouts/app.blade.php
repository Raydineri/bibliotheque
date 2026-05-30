<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Bibliothèque') }} - {{ $title ?? '' }}</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { background-color: #f1f5f9; }
        .sidebar { min-height: 100vh; background: linear-gradient(180deg, #1e3a5f 0%, #2d6a9f 100%); }
        .sidebar a { transition: all 0.2s; }
        .sidebar a:hover, .sidebar a.active { background: rgba(255,255,255,0.15); border-radius: 8px; }
        .card { background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); }
        .badge-available { background:#dcfce7; color:#16a34a; padding:3px 10px; border-radius:20px; font-size:12px; }
        .badge-unavailable { background:#fee2e2; color:#dc2626; padding:3px 10px; border-radius:20px; font-size:12px; }
        .badge-active { background:#dbeafe; color:#2563eb; padding:3px 10px; border-radius:20px; font-size:12px; }
        .badge-returned { background:#dcfce7; color:#16a34a; padding:3px 10px; border-radius:20px; font-size:12px; }
        .badge-overdue { background:#fee2e2; color:#dc2626; padding:3px 10px; border-radius:20px; font-size:12px; }
    </style>
</head>
<body>
<div class="flex">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="sidebar w-64 fixed top-0 left-0 h-full text-white z-50 flex flex-col">
        {{-- Logo --}}
        <div class="p-6 border-b border-white/20">
            <div class="flex items-center gap-3">
                <x-application-logo class="w-10 h-10" />
                <div>
                    <h1 class="font-bold text-lg leading-tight">BiblioTech</h1>
                    <p class="text-xs text-blue-200">Bibliothèque Numérique</p>
                </div>
            </div>
        </div>

        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">

            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 text-sm {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt w-5 text-center"></i>
                <span>Tableau de bord</span>
            </a>

            {{-- ===== SECTION CATALOGUE ===== --}}
            <div class="pt-4 pb-1 px-4 text-xs text-blue-300 uppercase tracking-wider font-semibold">Catalogue</div>

            <a href="{{ route('books.index') }}"
               class="flex items-center gap-3 px-4 py-3 text-sm {{ request()->routeIs('books.*') ? 'active' : '' }}">
                <i class="fas fa-book w-5 text-center"></i>
                <span>Livres</span>
            </a>

            @role('admin')
            <a href="{{ route('admin.authors.index') }}"
               class="flex items-center gap-3 px-4 py-3 text-sm {{ request()->routeIs('admin.authors.*') ? 'active' : '' }}">
                <i class="fas fa-user-pen w-5 text-center"></i>
                <span>Auteurs</span>
            </a>

            <a href="{{ route('admin.categories.index') }}"
               class="flex items-center gap-3 px-4 py-3 text-sm {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="fas fa-tags w-5 text-center"></i>
                <span>Catégories</span>
            </a>
            @endrole

            {{-- ===== SECTION GESTION ===== --}}
            <div class="pt-4 pb-1 px-4 text-xs text-blue-300 uppercase tracking-wider font-semibold">Gestion</div>

            <a href="{{ route('loans.index') }}"
               class="flex items-center gap-3 px-4 py-3 text-sm {{ request()->routeIs('loans.*') ? 'active' : '' }}">
                <i class="fas fa-hand-holding-heart w-5 text-center"></i>
                <span>{{ auth()->user()->hasRole('admin') ? 'Tous les emprunts' : 'Mes emprunts' }}</span>
            </a>

            @role('admin')
            {{-- ===== SECTION ADMIN ===== --}}
            <div class="pt-4 pb-1 px-4 text-xs text-blue-300 uppercase tracking-wider font-semibold">Administration</div>

            <a href="{{ route('admin.users.index') }}"
               class="flex items-center gap-3 px-4 py-3 text-sm {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users-cog w-5 text-center"></i>
                <span>Utilisateurs</span>
            </a>
            @endrole

            {{-- ===== SECTION IA ===== --}}
            <div class="pt-4 pb-1 px-4 text-xs text-blue-300 uppercase tracking-wider font-semibold">Assistant IA</div>

            <a href="{{ route('chatbot.index') }}"
               class="flex items-center gap-3 px-4 py-3 text-sm {{ request()->routeIs('chatbot.*') ? 'active' : '' }}">
                <i class="fas fa-robot w-5 text-center"></i>
                <span>BiblioBot</span>
                <span class="ml-auto bg-green-400 text-white text-xs px-2 py-0.5 rounded-full">IA</span>
            </a>

            @role('member')
            {{-- ===== SECTION MEMBRE ===== --}}
            <div class="pt-4 pb-1 px-4 text-xs text-blue-300 uppercase tracking-wider font-semibold">Mon Compte</div>

            <a href="{{ route('profile.show') }}"
               class="flex items-center gap-3 px-4 py-3 text-sm {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <i class="fas fa-user-circle w-5 text-center"></i>
                <span>Mon Profil</span>
            </a>
            @endrole

        </nav>

        {{-- User info --}}
        <div class="p-4 border-t border-white/20">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-white/20 rounded-full flex items-center justify-center">
                    <span class="text-sm font-bold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-blue-200">{{ auth()->user()->getRoleNames()->first() ?? 'membre' }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Déconnexion"
                            class="text-blue-200 hover:text-white transition">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ===== CONTENU PRINCIPAL ===== --}}
    <main class="ml-64 flex-1 min-h-screen">

        {{-- Topbar --}}
        <header class="bg-white shadow-sm px-8 py-4 flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-700">
                {{ $header ?? 'Tableau de bord' }}
            </h2>
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-500">
                    <i class="fas fa-calendar-alt mr-1"></i>
                    {{ now()->format('d/m/Y') }}
                </span>
                @if(auth()->user()->hasRole('admin'))
                    <span class="bg-purple-100 text-purple-700 text-xs px-3 py-1 rounded-full font-medium">
                        <i class="fas fa-crown mr-1"></i>Admin
                    </span>
                @endif
            </div>
        </header>

        {{-- Alertes --}}
        <div class="px-8 pt-4">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-4 flex items-center gap-2">
                    <i class="fas fa-check-circle text-green-500"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-4 flex items-center gap-2">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                    {{ session('error') }}
                </div>
            @endif
        </div>

        {{-- Page Content --}}
        <div class="p-8">
            {{ $slot }}
        </div>
    </main>
</div>
</body>
</html>

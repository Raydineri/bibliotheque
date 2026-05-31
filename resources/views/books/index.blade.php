<x-app-layout>
    <x-slot name="header">Catalogue des Livres</x-slot>

    {{-- Header + filtres --}}
    <div class="space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm text-slate-500">{{ $books->total() }} livre(s) dans le catalogue</p>
                <h1 class="text-2xl font-semibold text-slate-900">Catalogue des livres</h1>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                @if(auth()->user()->hasRole('admin'))
                    <a href="{{ route('books.create') }}" class="app-btn app-btn-success">
                        <i class="fas fa-plus"></i> Ajouter un livre
                    </a>
                @endif
            </div>
        </div>

        <form method="GET" action="{{ route('books.index') }}" class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            <div class="lg:col-span-2">
                <label class="text-xs font-semibold text-slate-500 uppercase tracking-widest">Recherche</label>
                <div class="relative mt-2">
                    <i class="fas fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Rechercher un livre ou auteur..." class="app-input pl-10">
                </div>
            </div>
            <div>
                <label class="text-xs font-semibold text-slate-500 uppercase tracking-widest">Categorie</label>
                <select name="category_id" class="app-select mt-2">
                    <option value="">Toutes categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-3">
                <button type="submit" class="app-btn app-btn-primary w-full justify-center">
                    <i class="fas fa-search"></i>
                    Rechercher
                </button>
                @if(request('search') || request('category_id'))
                    <a href="{{ route('books.index') }}" class="app-btn app-btn-secondary">
                        <i class="fas fa-times"></i>
                        Reinitialiser
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Grille de livres --}}
    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @forelse($books as $book)
            <div class="app-card overflow-hidden transition hover:-translate-y-0.5 hover:shadow-md">
                {{-- Header coloré --}}
                <div class="h-3 {{ $book->available_copies > 0 ? 'bg-gradient-to-r from-blue-500 to-blue-600' : 'bg-gradient-to-r from-gray-400 to-gray-500' }}"></div>

                <div class="p-5">
                    {{-- Icône et titre --}}
                    <div class="flex items-start gap-3 mb-3">
                        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-book text-blue-600 text-xl"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-slate-900 text-sm leading-tight line-clamp-2">{{ $book->title }}</h3>
                            <p class="text-xs text-slate-500 mt-1">{{ $book->author->name }}</p>
                        </div>
                    </div>

                    {{-- Infos --}}
                    <div class="space-y-1 mb-4">
                        <div class="flex items-center justify-between text-xs text-slate-500">
                            <span><i class="fas fa-tag mr-1"></i>{{ $book->category->name }}</span>
                            <span>{{ $book->published_year }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-500">Disponibles :</span>
                            <span class="{{ $book->available_copies > 0 ? 'text-emerald-600' : 'text-rose-500' }} font-semibold">
                                {{ $book->available_copies }} / {{ $book->total_copies }}
                            </span>
                        </div>
                    </div>

                    {{-- Badge dispo --}}
                    <div class="mb-4">
                        @if($book->available_copies > 0)
                            <span class="badge-available"><i class="fas fa-check mr-1"></i>Disponible</span>
                        @else
                            <span class="badge-unavailable"><i class="fas fa-times mr-1"></i>Indisponible</span>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-2">
                        <a href="{{ route('books.show', $book) }}"
                           class="app-btn app-btn-secondary flex-1 justify-center text-blue-600 hover:text-blue-700">
                            <i class="fas fa-eye"></i>Details
                        </a>
                        @if(auth()->user()->hasRole('admin'))
                            <a href="{{ route('books.edit', $book) }}"
                               class="app-btn app-btn-warning flex-1 justify-center">
                                <i class="fas fa-edit"></i>Modifier
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-4 text-center py-16">
                <i class="fas fa-book-open text-slate-300 text-5xl mb-4"></i>
                <p class="text-slate-500">Aucun livre trouve</p>
                @if(auth()->user()->hasRole('admin'))
                    <a href="{{ route('books.create') }}" class="mt-3 inline-block text-blue-600 hover:underline text-sm">
                        + Ajouter le premier livre
                    </a>
                @endif
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $books->withQueryString()->links() }}
    </div>
</x-app-layout>

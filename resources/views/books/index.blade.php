<x-app-layout>
    <x-slot name="header">Catalogue des Livres</x-slot>

    {{-- Barre de recherche + bouton ajouter --}}
    <div class="flex flex-col sm:flex-row gap-4 mb-6">
        <form method="GET" action="{{ route('books.index') }}" class="flex gap-2 flex-1">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Rechercher un livre ou auteur..."
                   class="flex-1 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            <select name="category_id" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none">
                <option value="">Toutes catégories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-xl text-sm hover:bg-blue-700 transition">
                <i class="fas fa-search"></i>
            </button>
            @if(request('search') || request('category_id'))
                <a href="{{ route('books.index') }}" class="bg-gray-200 text-gray-600 px-4 py-2 rounded-xl text-sm hover:bg-gray-300 transition">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </form>

        @if(auth()->user()->hasRole('admin'))
            <a href="{{ route('books.create') }}"
               class="bg-green-600 text-white px-5 py-2 rounded-xl text-sm font-medium hover:bg-green-700 transition flex items-center gap-2">
                <i class="fas fa-plus"></i> Ajouter un livre
            </a>
        @endif
    </div>

    {{-- Grille de livres --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @forelse($books as $book)
            <div class="card overflow-hidden hover:shadow-md transition">
                {{-- Header coloré --}}
                <div class="h-3 {{ $book->available_copies > 0 ? 'bg-gradient-to-r from-blue-500 to-blue-600' : 'bg-gradient-to-r from-gray-400 to-gray-500' }}"></div>

                <div class="p-5">
                    {{-- Icône et titre --}}
                    <div class="flex items-start gap-3 mb-3">
                        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-book text-blue-600 text-xl"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-800 text-sm leading-tight line-clamp-2">{{ $book->title }}</h3>
                            <p class="text-xs text-gray-500 mt-1">{{ $book->author->name }}</p>
                        </div>
                    </div>

                    {{-- Infos --}}
                    <div class="space-y-1 mb-4">
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span><i class="fas fa-tag mr-1"></i>{{ $book->category->name }}</span>
                            <span>{{ $book->published_year }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-500">Disponibles :</span>
                            <span class="{{ $book->available_copies > 0 ? 'text-green-600' : 'text-red-500' }} font-semibold">
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
                           class="flex-1 text-center bg-blue-50 text-blue-600 py-1.5 rounded-lg text-xs font-medium hover:bg-blue-100 transition">
                            <i class="fas fa-eye mr-1"></i>Détails
                        </a>
                        @if(auth()->user()->hasRole('admin'))
                            <a href="{{ route('books.edit', $book) }}"
                               class="flex-1 text-center bg-yellow-50 text-yellow-600 py-1.5 rounded-lg text-xs font-medium hover:bg-yellow-100 transition">
                                <i class="fas fa-edit mr-1"></i>Modifier
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-4 text-center py-16">
                <i class="fas fa-book-open text-gray-300 text-5xl mb-4"></i>
                <p class="text-gray-500">Aucun livre trouvé</p>
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

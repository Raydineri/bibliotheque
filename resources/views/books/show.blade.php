<x-app-layout>
    <x-slot name="header">Détail du livre</x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="card p-8">
            <div class="flex flex-col md:flex-row gap-8">
                {{-- Icône --}}
                <div class="w-32 h-40 bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl flex items-center justify-center flex-shrink-0 mx-auto md:mx-0">
                    <i class="fas fa-book text-white text-5xl"></i>
                </div>

                {{-- Infos --}}
                <div class="flex-1">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">{{ $book->title }}</h1>
                            <p class="text-gray-500 mt-1">par <span class="font-medium text-blue-600">{{ $book->author->name }}</span></p>
                        </div>
                        @if($book->available_copies > 0)
                            <span class="badge-available text-sm"><i class="fas fa-check mr-1"></i>Disponible</span>
                        @else
                            <span class="badge-unavailable text-sm">Indisponible</span>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-xs text-gray-400 mb-1">Catégorie</p>
                            <p class="font-medium text-sm">{{ $book->category->name }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-xs text-gray-400 mb-1">Année de publication</p>
                            <p class="font-medium text-sm">{{ $book->published_year ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-xs text-gray-400 mb-1">ISBN</p>
                            <p class="font-medium text-sm">{{ $book->isbn ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-xs text-gray-400 mb-1">Exemplaires</p>
                            <p class="font-medium text-sm">
                                <span class="text-green-600">{{ $book->available_copies }}</span> disponibles / {{ $book->total_copies }} total
                            </p>
                        </div>
                    </div>

                    @if($book->description)
                        <p class="text-gray-600 text-sm leading-relaxed mb-6">{{ $book->description }}</p>
                    @endif

                    {{-- Actions --}}
                    <div class="flex gap-3 flex-wrap">
                        @if($book->available_copies > 0)
                            <form method="POST" action="{{ route('loans.store') }}">
                                @csrf
                                <input type="hidden" name="book_id" value="{{ $book->id }}">
                                <button type="submit"
                                        class="bg-blue-600 text-white px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-blue-700 transition flex items-center gap-2">
                                    <i class="fas fa-hand-holding-heart"></i> Emprunter ce livre
                                </button>
                            </form>
                        @endif

                        @if(auth()->user()->hasRole('admin'))
                            <a href="{{ route('books.edit', $book) }}"
                               class="bg-yellow-500 text-white px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-yellow-600 transition flex items-center gap-2">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                            <form method="POST" action="{{ route('books.destroy', $book) }}" onsubmit="return confirm('Supprimer ce livre ?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="bg-red-500 text-white px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-red-600 transition flex items-center gap-2">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('books.index') }}"
                           class="bg-gray-100 text-gray-600 px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-200 transition flex items-center gap-2">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

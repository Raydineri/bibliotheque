<x-app-layout>
    <x-slot name="header">Tableau de bord</x-slot>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-8">
        <div class="card p-5 col-span-1">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-book text-blue-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total_books'] }}</p>
                    <p class="text-xs text-gray-500">Total Livres</p>
                </div>
            </div>
        </div>
        <div class="card p-5">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['available_books'] }}</p>
                    <p class="text-xs text-gray-500">Disponibles</p>
                </div>
            </div>
        </div>
        <div class="card p-5">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-hand-holding-heart text-yellow-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['active_loans'] }}</p>
                    <p class="text-xs text-gray-500">Emprunts actifs</p>
                </div>
            </div>
        </div>
        <div class="card p-5">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 bg-red-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-red-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['overdue_loans'] }}</p>
                    <p class="text-xs text-gray-500">En retard</p>
                </div>
            </div>
        </div>
        <div class="card p-5">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-purple-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total_members'] }}</p>
                    <p class="text-xs text-gray-500">Membres</p>
                </div>
            </div>
        </div>
        <div class="card p-5">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-history text-indigo-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total_loans'] }}</p>
                    <p class="text-xs text-gray-500">Total emprunts</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Livres les plus empruntés --}}
        <div class="card p-6">
            <h3 class="font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <i class="fas fa-fire text-orange-500"></i> Livres les plus empruntés
            </h3>
            @forelse($mostBorrowed as $book)
                <div class="flex items-center justify-between py-3 border-b last:border-0">
                    <div>
                        <p class="font-medium text-sm text-gray-800">{{ $book->title }}</p>
                        <p class="text-xs text-gray-400">{{ $book->author->name ?? '' }}</p>
                    </div>
                    <span class="bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full font-medium">
                        {{ $book->loans_count }} emprunts
                    </span>
                </div>
            @empty
                <p class="text-gray-400 text-sm text-center py-4">Aucun emprunt enregistré</p>
            @endforelse
        </div>

        {{-- Mes emprunts (membre) ou Retards (admin) --}}
        @if(auth()->user()->hasRole('admin') && $overdueLoans->count())
            <div class="card p-6">
                <h3 class="font-semibold text-gray-700 mb-4 flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle text-red-500"></i>
                    Emprunts en retard ({{ $overdueLoans->count() }})
                </h3>
                @foreach($overdueLoans as $loan)
                    <div class="flex items-center justify-between py-3 border-b last:border-0">
                        <div>
                            <p class="font-medium text-sm text-gray-800">{{ $loan->book->title }}</p>
                            <p class="text-xs text-gray-400">{{ $loan->user->name }}</p>
                        </div>
                        <span class="badge-overdue">
                            {{ $loan->due_date->diffForHumans() }}
                        </span>
                    </div>
                @endforeach
            </div>
        @else
            <div class="card p-6">
                <h3 class="font-semibold text-gray-700 mb-4 flex items-center gap-2">
                    <i class="fas fa-book-reader text-blue-500"></i> Mes emprunts actifs
                </h3>
                @forelse($myLoans as $loan)
                    <div class="flex items-center justify-between py-3 border-b last:border-0">
                        <div>
                            <p class="font-medium text-sm text-gray-800">{{ $loan->book->title }}</p>
                            <p class="text-xs text-gray-400">Retour avant le {{ $loan->due_date->format('d/m/Y') }}</p>
                        </div>
                        @if($loan->due_date->isPast())
                            <span class="badge-overdue">En retard</span>
                        @else
                            <span class="badge-active">Actif</span>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-400 text-sm text-center py-4">Aucun emprunt actif</p>
                @endforelse
                <div class="mt-4">
                    <a href="{{ route('books.index') }}"
                       class="block text-center bg-blue-600 text-white py-2 rounded-xl text-sm hover:bg-blue-700 transition">
                        <i class="fas fa-search mr-1"></i> Parcourir le catalogue
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>

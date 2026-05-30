<x-app-layout>
    <x-slot name="header">Mon Profil</x-slot>

    <div class="max-w-4xl mx-auto space-y-6">

        {{-- Carte profil --}}
        <div class="card p-6">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center text-2xl font-bold text-blue-600">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-gray-800">{{ $user->name }}</h2>
                    <p class="text-gray-400 text-sm">{{ $user->email }}</p>
                    @if($user->phone)
                        <p class="text-gray-400 text-sm"><i class="fas fa-phone mr-1"></i>{{ $user->phone }}</p>
                    @endif
                    <p class="text-gray-400 text-xs mt-1">
                        <i class="fas fa-calendar mr-1"></i>Membre depuis le
                        {{ $user->member_since?->format('d/m/Y') ?? $user->created_at->format('d/m/Y') }}
                    </p>
                </div>
                <a href="{{ route('profile.edit') }}"
                   class="bg-blue-600 text-white px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-blue-700 transition">
                    <i class="fas fa-edit mr-1"></i>Modifier mon profil
                </a>
            </div>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-4 gap-4">
            <div class="card p-4 text-center">
                <p class="text-2xl font-bold text-blue-600">{{ $stats['total_loans'] }}</p>
                <p class="text-xs text-gray-500 mt-1">Total emprunts</p>
            </div>
            <div class="card p-4 text-center">
                <p class="text-2xl font-bold text-green-600">{{ $stats['active_loans'] }}</p>
                <p class="text-xs text-gray-500 mt-1">En cours</p>
            </div>
            <div class="card p-4 text-center">
                <p class="text-2xl font-bold text-gray-500">{{ $stats['returned_loans'] }}</p>
                <p class="text-xs text-gray-500 mt-1">Retournés</p>
            </div>
            <div class="card p-4 text-center">
                <p class="text-2xl font-bold text-red-500">{{ $stats['overdue_loans'] }}</p>
                <p class="text-xs text-gray-500 mt-1">En retard</p>
            </div>
        </div>

        {{-- Mes emprunts --}}
        <div class="card overflow-hidden">
            <div class="p-5 border-b flex items-center justify-between">
                <h3 class="font-semibold text-gray-700">
                    <i class="fas fa-book-reader text-blue-500 mr-2"></i>Mes emprunts
                </h3>
                <a href="{{ route('books.index') }}"
                   class="text-sm text-blue-600 hover:underline">
                    <i class="fas fa-plus mr-1"></i>Emprunter un livre
                </a>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-6 py-3 text-gray-600 font-semibold">Livre</th>
                    <th class="text-left px-6 py-3 text-gray-600 font-semibold">Emprunté le</th>
                    <th class="text-left px-6 py-3 text-gray-600 font-semibold">Retour avant le</th>
                    <th class="text-left px-6 py-3 text-gray-600 font-semibold">Statut</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse($loans as $loan)
                    <tr>
                        <td class="px-6 py-3">
                            <p class="font-medium text-gray-800">{{ $loan->book->title }}</p>
                            <p class="text-xs text-gray-400">{{ $loan->book->author->name }}</p>
                        </td>
                        <td class="px-6 py-3 text-gray-500">{{ $loan->borrowed_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-3 {{ $loan->status === 'active' && $loan->due_date->isPast() ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                            {{ $loan->due_date->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-3">
                            @if($loan->status === 'returned')
                                <span class="badge-returned">Retourné</span>
                            @elseif($loan->due_date->isPast())
                                <span class="badge-overdue">En retard !</span>
                            @else
                                <span class="badge-active">Actif</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-10 text-gray-400">
                            Aucun emprunt.
                            <a href="{{ route('books.index') }}" class="text-blue-500 hover:underline ml-1">
                                Parcourir le catalogue →
                            </a>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">Profil de {{ $user->name }}</x-slot>

    <div class="max-w-4xl mx-auto space-y-6">

        {{-- Infos utilisateur --}}
        <div class="card p-6">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-2xl font-bold
                    {{ $user->hasRole('admin') ? 'bg-purple-100 text-purple-600' : 'bg-blue-100 text-blue-600' }}">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-3">
                        <h2 class="text-xl font-bold text-gray-800">{{ $user->name }}</h2>
                        @if($user->hasRole('admin'))
                            <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-medium">
                                <i class="fas fa-crown mr-1"></i>Admin
                            </span>
                        @else
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-medium">
                                <i class="fas fa-user mr-1"></i>Membre
                            </span>
                        @endif
                        @if(!$user->is_active)
                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-medium">
                                <i class="fas fa-ban mr-1"></i>Inactif
                            </span>
                        @endif
                    </div>
                    <p class="text-gray-400 text-sm mt-1">{{ $user->email }}</p>
                    @if($user->phone)
                        <p class="text-gray-400 text-sm">{{ $user->phone }}</p>
                    @endif
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.users.edit', $user) }}"
                       class="bg-yellow-500 text-white px-4 py-2 rounded-xl text-sm hover:bg-yellow-600 transition">
                        <i class="fas fa-edit mr-1"></i>Modifier
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                       class="bg-gray-100 text-gray-600 px-4 py-2 rounded-xl text-sm hover:bg-gray-200 transition">
                        <i class="fas fa-arrow-left mr-1"></i>Retour
                    </a>
                </div>
            </div>
        </div>

        {{-- Stats emprunts --}}
        <div class="grid grid-cols-3 gap-4">
            <div class="card p-4 text-center">
                <p class="text-2xl font-bold text-blue-600">{{ $loans->count() }}</p>
                <p class="text-xs text-gray-500 mt-1">Total emprunts</p>
            </div>
            <div class="card p-4 text-center">
                <p class="text-2xl font-bold text-green-600">{{ $loans->where('status', 'active')->count() }}</p>
                <p class="text-xs text-gray-500 mt-1">Emprunts actifs</p>
            </div>
            <div class="card p-4 text-center">
                <p class="text-2xl font-bold text-red-500">
                    {{ $loans->filter(fn($l) => $l->status === 'active' && $l->due_date->isPast())->count() }}
                </p>
                <p class="text-xs text-gray-500 mt-1">En retard</p>
            </div>
        </div>

        {{-- Historique emprunts --}}
        <div class="card overflow-hidden">
            <div class="p-5 border-b">
                <h3 class="font-semibold text-gray-700">
                    <i class="fas fa-history text-blue-500 mr-2"></i>Historique des emprunts
                </h3>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-6 py-3 text-gray-600 font-semibold">Livre</th>
                    <th class="text-left px-6 py-3 text-gray-600 font-semibold">Emprunté le</th>
                    <th class="text-left px-6 py-3 text-gray-600 font-semibold">Retour prévu</th>
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
                        <td class="px-6 py-3 text-gray-500">{{ $loan->due_date->format('d/m/Y') }}</td>
                        <td class="px-6 py-3">
                            @if($loan->status === 'returned')
                                <span class="badge-returned">Retourné</span>
                            @elseif($loan->due_date->isPast())
                                <span class="badge-overdue">En retard</span>
                            @else
                                <span class="badge-active">Actif</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center py-8 text-gray-400">Aucun emprunt</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>


<x-app-layout>
    <x-slot name="header">Gestion des Utilisateurs</x-slot>

    {{-- Statistiques rapides --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="card p-4 flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-users text-blue-600"></i>
            </div>
            <div>
                <p class="text-xl font-bold text-gray-800">{{ $users->total() }}</p>
                <p class="text-xs text-gray-500">Total utilisateurs</p>
            </div>
        </div>
        <div class="card p-4 flex items-center gap-3">
            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-crown text-purple-600"></i>
            </div>
            <div>
                <p class="text-xl font-bold text-gray-800">
                    {{ \App\Models\User::role('admin')->count() }}
                </p>
                <p class="text-xs text-gray-500">Administrateurs</p>
            </div>
        </div>
        <div class="card p-4 flex items-center gap-3">
            <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-user-check text-green-600"></i>
            </div>
            <div>
                <p class="text-xl font-bold text-gray-800">
                    {{ \App\Models\User::where('is_active', true)->count() }}
                </p>
                <p class="text-xs text-gray-500">Comptes actifs</p>
            </div>
        </div>
    </div>

    {{-- Filtres + Bouton ajouter --}}
    <div class="flex flex-col sm:flex-row gap-4 mb-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-2 flex-1">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Rechercher par nom ou email..."
                   class="flex-1 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            <select name="role" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none">
                <option value="">Tous les rôles</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>
                        {{ ucfirst($role->name) }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-xl text-sm hover:bg-blue-700 transition">
                <i class="fas fa-search"></i>
            </button>
            @if(request('search') || request('role'))
                <a href="{{ route('admin.users.index') }}" class="bg-gray-200 text-gray-600 px-4 py-2 rounded-xl text-sm hover:bg-gray-300 transition">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </form>
        <a href="{{ route('admin.users.create') }}"
           class="bg-green-600 text-white px-5 py-2 rounded-xl text-sm font-medium hover:bg-green-700 transition flex items-center gap-2">
            <i class="fas fa-user-plus"></i> Ajouter
        </a>
    </div>

    {{-- Tableau --}}
    <div class="card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
            <tr>
                <th class="text-left px-6 py-4 text-gray-600 font-semibold">Utilisateur</th>
                <th class="text-left px-6 py-4 text-gray-600 font-semibold">Rôle</th>
                <th class="text-left px-6 py-4 text-gray-600 font-semibold">Téléphone</th>
                <th class="text-left px-6 py-4 text-gray-600 font-semibold">Emprunts</th>
                <th class="text-left px-6 py-4 text-gray-600 font-semibold">Statut</th>
                <th class="text-left px-6 py-4 text-gray-600 font-semibold">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm
                                    {{ $user->hasRole('admin') ? 'bg-purple-100 text-purple-600' : 'bg-blue-100 text-blue-600' }}">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($user->hasRole('admin'))
                            <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-medium">
                                    <i class="fas fa-crown mr-1"></i>Admin
                                </span>
                        @else
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-medium">
                                    <i class="fas fa-user mr-1"></i>Membre
                                </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-xs">{{ $user->phone ?? '—' }}</td>
                    <td class="px-6 py-4">
                        <span class="text-gray-700 font-medium">{{ $user->loans_count }}</span>
                        @if($user->active_loans_count > 0)
                            <span class="text-xs text-blue-500 ml-1">({{ $user->active_loans_count }} actif)</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($user->is_active)
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">
                                    <i class="fas fa-circle text-green-500 mr-1" style="font-size:8px"></i>Actif
                                </span>
                        @else
                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-medium">
                                    <i class="fas fa-circle text-red-500 mr-1" style="font-size:8px"></i>Inactif
                                </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.users.show', $user) }}"
                               class="bg-blue-100 text-blue-700 p-2 rounded-lg hover:bg-blue-200 transition" title="Voir profil">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            <a href="{{ route('admin.users.edit', $user) }}"
                               class="bg-yellow-100 text-yellow-700 p-2 rounded-lg hover:bg-yellow-200 transition" title="Modifier">
                                <i class="fas fa-edit text-xs"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="{{ $user->is_active ? 'bg-orange-100 text-orange-700 hover:bg-orange-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} p-2 rounded-lg transition"
                                        title="{{ $user->is_active ? 'Désactiver' : 'Activer' }}">
                                    <i class="fas {{ $user->is_active ? 'fa-ban' : 'fa-check' }} text-xs"></i>
                                </button>
                            </form>
                            @if($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                                    @csrf @method('DELETE')
                                    <button class="bg-red-100 text-red-700 p-2 rounded-lg hover:bg-red-200 transition" title="Supprimer">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center py-12 text-gray-400">Aucun utilisateur trouvé</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $users->withQueryString()->links() }}</div>
</x-app-layout>


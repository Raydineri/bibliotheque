<x-app-layout>
    <x-slot name="header">Modifier l'utilisateur</x-slot>

    <div class="max-w-2xl mx-auto card p-8">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-5">
            @csrf @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rôle *</label>
                <div class="flex gap-4 mt-2">
                    @foreach($roles as $role)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="role" value="{{ $role->name }}"
                                   {{ $user->hasRole($role->name) ? 'checked' : '' }}
                                   class="text-blue-600">
                            <span class="text-sm {{ $role->name === 'admin' ? 'text-purple-600 font-medium' : 'text-gray-700' }}">
                                {{ $role->name === 'admin' ? '👑 Administrateur' : '👤 Membre' }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1"
                           {{ $user->is_active ? 'checked' : '' }}
                           class="rounded text-blue-600">
                    <span class="text-sm text-gray-700">Compte actif</span>
                </label>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nouveau mot de passe <span class="text-gray-400">(laisser vide = inchangé)</span>
                    </label>
                    <input type="password" name="password"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirmer</label>
                    <input type="password" name="password_confirmation"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-1"></i> Mettre à jour
                </button>
                <a href="{{ route('admin.users.index') }}"
                   class="bg-gray-100 text-gray-600 px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-200 transition">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</x-app-layout>


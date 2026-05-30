<x-app-layout>
    <x-slot name="header">Créer un utilisateur</x-slot>

    <div class="max-w-2xl mx-auto card p-8">
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet *</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rôle *</label>
                <div class="flex gap-4 mt-2">
                    @foreach($roles as $role)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="role" value="{{ $role->name }}"
                                   {{ old('role', 'member') === $role->name ? 'checked' : '' }}
                                   class="text-blue-600">
                            <span class="text-sm {{ $role->name === 'admin' ? 'text-purple-600 font-medium' : 'text-gray-700' }}">
                                {{ $role->name === 'admin' ? '👑 Administrateur' : '👤 Membre' }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe *</label>
                    <input type="password" name="password"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirmer *</label>
                    <input type="password" name="password_confirmation"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-green-600 text-white px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-green-700 transition">
                    <i class="fas fa-user-plus mr-1"></i> Créer l'utilisateur
                </button>
                <a href="{{ route('admin.users.index') }}"
                   class="bg-gray-100 text-gray-600 px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-200 transition">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</x-app-layout>


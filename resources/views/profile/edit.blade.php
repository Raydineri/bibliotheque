<x-app-layout>
    <x-slot name="header">Modifier mon profil</x-slot>

    <div class="max-w-xl mx-auto card p-8">
        <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet *</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <hr class="my-2">
            <p class="text-xs text-gray-400">Laisser vide pour ne pas changer le mot de passe</p>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe</label>
                    <input type="password" name="password"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
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
                    <i class="fas fa-save mr-1"></i> Sauvegarder
                </button>
                <a href="{{ route('profile.show') }}"
                   class="bg-gray-100 text-gray-600 px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-200 transition">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</x-app-layout>

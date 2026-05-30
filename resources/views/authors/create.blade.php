<x-app-layout>
    <x-slot name="header">Ajouter un auteur</x-slot>
    <div class="max-w-xl mx-auto card p-8">
        <form method="POST" action="{{ route('authors.store') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nationalité</label>
                <input type="text" name="nationality" value="{{ old('nationality') }}"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Biographie</label>
                <textarea name="bio" rows="4"
                          class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">{{ old('bio') }}</textarea>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="bg-green-600 text-white px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-green-700 transition">
                    <i class="fas fa-save mr-1"></i> Enregistrer
                </button>
                <a href="{{ route('authors.index') }}" class="bg-gray-100 text-gray-600 px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-200 transition">Annuler</a>
            </div>
        </form>
    </div>
</x-app-layout>

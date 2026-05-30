<x-app-layout>
    <x-slot name="header">Modifier l'auteur</x-slot>
    <div class="max-w-xl mx-auto card p-8">
        <form method="POST" action="{{ route('admin.authors.update', $author) }}" class="space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                <input type="text" name="name" value="{{ old('name', $author->name) }}"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nationalité</label>
                <input type="text" name="nationality" value="{{ old('nationality', $author->nationality) }}"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Biographie</label>
                <textarea name="bio" rows="4"
                          class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">{{ old('bio', $author->bio) }}</textarea>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-1"></i> Mettre à jour
                </button>
                <a href="{{ route('admin.authors.index') }}" class="bg-gray-100 text-gray-600 px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-200 transition">Annuler</a>
            </div>
        </form>
    </div>
</x-app-layout>

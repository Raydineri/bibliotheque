<x-app-layout>
    <x-slot name="header">Gestion des Catégories</x-slot>

    <div class="flex justify-between items-center mb-6">
        <p class="text-gray-500 text-sm">{{ $categories->total() }} catégorie(s)</p>
        <a href="{{ route('admin.categories.create') }}"
           class="bg-green-600 text-white px-5 py-2 rounded-xl text-sm font-medium hover:bg-green-700 transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Ajouter
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($categories as $cat)
            <div class="card p-5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-tag text-indigo-600"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">{{ $cat->name }}</p>
                        <p class="text-xs text-gray-400">{{ $cat->books_count }} livre(s)</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.categories.edit', $cat) }}"
                       class="bg-yellow-100 text-yellow-700 p-2 rounded-lg hover:bg-yellow-200 transition">
                        <i class="fas fa-edit text-xs"></i>
                    </a>
                    <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}"
                          onsubmit="return confirm('Supprimer cette catégorie ?')">
                        @csrf @method('DELETE')
                        <button class="bg-red-100 text-red-700 p-2 rounded-lg hover:bg-red-200 transition">
                            <i class="fas fa-trash text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-10 text-gray-400">Aucune catégorie</div>
        @endforelse
    </div>
    <div class="mt-4">{{ $categories->links() }}</div>
</x-app-layout>

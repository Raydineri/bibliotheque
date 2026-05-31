<x-app-layout>
    <x-slot name="header">Details de la categorie</x-slot>

    <div class="space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm text-slate-500">Categorie #{{ $category->id }}</p>
                <h1 class="text-2xl font-semibold text-slate-900">{{ $category->name }}</h1>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.categories.index') }}" class="app-btn app-btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Retour
                </a>
                <a href="{{ route('admin.categories.edit', $category) }}" class="app-btn app-btn-warning">
                    <i class="fas fa-pen"></i>
                    Modifier
                </a>
                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}">
                    @csrf @method('DELETE')
                    <button class="app-btn app-btn-danger">
                        <i class="fas fa-trash"></i>
                        Supprimer
                    </button>
                </form>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <div class="app-card p-5">
                <p class="text-xs text-slate-500 uppercase tracking-widest">Livres</p>
                <div class="mt-3 flex items-center justify-between">
                    <p class="text-2xl font-semibold text-slate-900">{{ $category->books_count }}</p>
                    <span class="app-badge app-badge-info">
                        <i class="fas fa-book"></i>Catalogue
                    </span>
                </div>
            </div>
            <div class="app-card p-5">
                <p class="text-xs text-slate-500 uppercase tracking-widest">Description</p>
                <div class="mt-3">
                    <p class="text-sm text-slate-600">{{ $category->description ?: 'Aucune description.' }}</p>
                </div>
            </div>
            <div class="app-card p-5">
                <p class="text-xs text-slate-500 uppercase tracking-widest">Identifiant</p>
                <div class="mt-3">
                    <p class="text-lg font-semibold text-slate-900">#{{ $category->id }}</p>
                </div>
            </div>
        </div>

        <div class="app-card p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-slate-900">Livres de la categorie</h2>
                <span class="text-xs text-slate-400">{{ $category->books_count }} livre(s)</span>
            </div>
            <div class="overflow-x-auto">
                <table class="app-table">
                    <thead>
                    <tr>
                        <th>Titre</th>
                        <th class="text-right">ID</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($category->books as $book)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="text-slate-900 font-medium">{{ $book->title }}</td>
                            <td class="text-right text-slate-500">#{{ $book->id }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-6 py-14">
                                <div class="flex flex-col items-center gap-3 text-slate-500">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center">
                                        <i class="fas fa-book"></i>
                                    </div>
                                    <p class="text-sm">Aucun livre associe</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>


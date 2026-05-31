<x-app-layout>
    <x-slot name="header">Gestion des Catégories</x-slot>

    <div class="space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm text-slate-500">{{ $categories->total() }} categorie(s) enregistree(s)</p>
                <h1 class="text-2xl font-semibold text-slate-900">Categories</h1>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.categories.create') }}" class="app-btn app-btn-success">
                    <i class="fas fa-plus"></i>
                    Ajouter une categorie
                </a>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <div class="app-card p-5">
                <p class="text-xs text-slate-500 uppercase tracking-widest">Total categories</p>
                <div class="mt-3 flex items-center justify-between">
                    <p class="text-2xl font-semibold text-slate-900">{{ $categories->total() }}</p>
                    <span class="app-badge app-badge-info">
                        <i class="fas fa-tags"></i>Catalogue
                    </span>
                </div>
            </div>
            <div class="app-card p-5">
                <p class="text-xs text-slate-500 uppercase tracking-widest">Page actuelle</p>
                <div class="mt-3 flex items-center justify-between">
                    <p class="text-2xl font-semibold text-slate-900">{{ $categories->count() }}</p>
                    <span class="app-badge app-badge-success">
                        <i class="fas fa-layer-group"></i>{{ $categories->currentPage() }} / {{ $categories->lastPage() }}
                    </span>
                </div>
            </div>
            <div class="app-card p-5">
                <p class="text-xs text-slate-500 uppercase tracking-widest">Acces rapide</p>
                <div class="mt-3 flex items-center gap-3">
                    <a href="{{ route('admin.authors.index') }}" class="app-btn app-btn-ghost">
                        <i class="fas fa-user-pen"></i>Auteurs
                    </a>
                    <a href="{{ route('books.index') }}" class="app-btn app-btn-ghost">
                        <i class="fas fa-book"></i>Livres
                    </a>
                </div>
            </div>
        </div>

        <div class="app-card p-6">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Liste des categories</h2>
                    <p class="text-sm text-slate-500">Filtrer, trier et gerer les categories du catalogue.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <button type="button" class="app-btn app-btn-secondary">
                        <i class="fas fa-sliders"></i>
                        Colonnes
                    </button>
                    <button type="button" class="app-btn app-btn-secondary">
                        <i class="fas fa-arrow-down-wide-short"></i>
                        Trier
                    </button>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.categories.index') }}" class="mt-6 grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <div class="lg:col-span-2">
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-widest">Recherche</label>
                    <div class="relative mt-2">
                        <i class="fas fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" name="q" class="app-input pl-10" placeholder="Nom ou description">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-widest">Etat</label>
                    <select class="app-select mt-2" disabled>
                        <option>Toutes</option>
                    </select>
                </div>
                <div class="flex items-end gap-3">
                    <button type="submit" class="app-btn app-btn-primary w-full justify-center">
                        <i class="fas fa-filter"></i>
                        Filtrer
                    </button>
                </div>
            </form>

            <div class="mt-6 overflow-x-auto">
                <table class="app-table">
                    <thead>
                    <tr>
                        <th>
                            <div class="flex items-center gap-2">
                                Nom
                                <button type="button" class="text-slate-400 hover:text-slate-600">
                                    <i class="fas fa-sort"></i>
                                </button>
                            </div>
                        </th>
                        <th>Description</th>
                        <th>
                            <div class="flex items-center gap-2">
                                Livres
                                <button type="button" class="text-slate-400 hover:text-slate-600">
                                    <i class="fas fa-sort"></i>
                                </button>
                            </div>
                        </th>
                        <th class="text-right">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($categories as $cat)
                        <tr class="hover:bg-slate-50 transition">
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-indigo-50 rounded-full flex items-center justify-center">
                                        <i class="fas fa-tag text-indigo-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-900">{{ $cat->name }}</p>
                                        <p class="text-xs text-slate-500">ID #{{ $cat->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="text-slate-600">
                                <span class="line-clamp-2">{{ $cat->description ?? '—' }}</span>
                            </td>
                            <td>
                                <span class="app-badge app-badge-info">
                                    {{ $cat->books_count }} livre(s)
                                </span>
                            </td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.categories.show', $cat) }}"
                                       class="app-btn app-btn-secondary text-blue-600 hover:text-blue-700" title="Voir" aria-label="Voir les details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.categories.edit', $cat) }}"
                                       class="app-btn app-btn-warning" title="Modifier" aria-label="Modifier">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}">
                                        @csrf @method('DELETE')
                                        <button class="app-btn app-btn-danger" title="Supprimer" aria-label="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-14">
                                <div class="flex flex-col items-center gap-3 text-slate-500">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center">
                                        <i class="fas fa-tags"></i>
                                    </div>
                                    <p class="text-sm">Aucune categorie enregistree</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <p class="text-sm text-slate-500">
                Affichage de {{ $categories->count() }} categorie(s) sur {{ $categories->total() }}
            </p>
            <div class="mt-2">{{ $categories->links() }}</div>
        </div>
    </div>
</x-app-layout>

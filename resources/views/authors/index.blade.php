<x-app-layout>
    <x-slot name="header">Gestion des Auteurs</x-slot>

    <div class="flex justify-between items-center mb-6">
        <p class="text-gray-500 text-sm">{{ $authors->total() }} auteur(s) enregistré(s)</p>
        <a href="{{ route('authors.create') }}"
           class="bg-green-600 text-white px-5 py-2 rounded-xl text-sm font-medium hover:bg-green-700 transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Ajouter un auteur
        </a>
    </div>

    <div class="card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
            <tr>
                <th class="text-left px-6 py-4 text-gray-600 font-semibold">Nom</th>
                <th class="text-left px-6 py-4 text-gray-600 font-semibold">Nationalité</th>
                <th class="text-left px-6 py-4 text-gray-600 font-semibold">Livres</th>
                <th class="text-left px-6 py-4 text-gray-600 font-semibold">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            @forelse($authors as $author)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-purple-100 rounded-full flex items-center justify-center">
                                <span class="text-purple-600 font-semibold text-sm">{{ strtoupper(substr($author->name, 0, 1)) }}</span>
                            </div>
                            <span class="font-medium text-gray-800">{{ $author->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $author->nationality ?? '—' }}</td>
                    <td class="px-6 py-4">
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-medium">
                                {{ $author->books_count }} livre(s)
                            </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <a href="{{ route('authors.edit', $author) }}"
                               class="bg-yellow-100 text-yellow-700 px-3 py-1.5 rounded-lg text-xs hover:bg-yellow-200 transition">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('authors.destroy', $author) }}"
                                  onsubmit="return confirm('Supprimer cet auteur ?')">
                                @csrf @method('DELETE')
                                <button class="bg-red-100 text-red-700 px-3 py-1.5 rounded-lg text-xs hover:bg-red-200 transition">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center py-10 text-gray-400">Aucun auteur</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $authors->links() }}</div>
</x-app-layout>

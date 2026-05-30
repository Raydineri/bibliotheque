<x-app-layout>
    <x-slot name="header">
        {{ auth()->user()->hasRole('admin') ? 'Tous les Emprunts' : 'Mes Emprunts' }}
    </x-slot>

    <div class="card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
            <tr>
                @if(auth()->user()->hasRole('admin'))
                    <th class="text-left px-6 py-4 text-gray-600 font-semibold">Membre</th>
                @endif
                <th class="text-left px-6 py-4 text-gray-600 font-semibold">Livre</th>
                <th class="text-left px-6 py-4 text-gray-600 font-semibold">Emprunté le</th>
                <th class="text-left px-6 py-4 text-gray-600 font-semibold">Retour prévu</th>
                <th class="text-left px-6 py-4 text-gray-600 font-semibold">Statut</th>
                <th class="text-left px-6 py-4 text-gray-600 font-semibold">Action</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            @forelse($loans as $loan)
                <tr class="hover:bg-gray-50 transition">
                    @if(auth()->user()->hasRole('admin'))
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-xs text-blue-600 font-bold">{{ strtoupper(substr($loan->user->name, 0, 1)) }}</span>
                                </div>
                                <span class="text-gray-700">{{ $loan->user->name }}</span>
                            </div>
                        </td>
                    @endif
                    <td class="px-6 py-4">
                        <p class="font-medium text-gray-800">{{ $loan->book->title }}</p>
                        <p class="text-xs text-gray-400">{{ $loan->book->author->name }}</p>
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $loan->borrowed_at->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 {{ $loan->due_date->isPast() && $loan->status === 'active' ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                        {{ $loan->due_date->format('d/m/Y') }}
                        @if($loan->due_date->isPast() && $loan->status === 'active')
                            <span class="text-xs block">⚠ En retard</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($loan->status === 'active' && $loan->due_date->isPast())
                            <span class="badge-overdue">En retard</span>
                        @elseif($loan->status === 'active')
                            <span class="badge-active">Actif</span>
                        @else
                            <span class="badge-returned">Retourné</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($loan->status === 'active')
                            <form method="POST" action="{{ route('loans.return', $loan) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="bg-green-600 text-white px-3 py-1.5 rounded-lg text-xs hover:bg-green-700 transition">
                                    <i class="fas fa-undo mr-1"></i>Retourner
                                </button>
                            </form>
                        @else
                            <span class="text-gray-300 text-xs">{{ $loan->returned_at?->format('d/m/Y') }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-12 text-gray-400">
                        <i class="fas fa-hand-holding-heart text-4xl mb-3 block"></i>
                        Aucun emprunt enregistré
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($loans instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="mt-4">{{ $loans->links() }}</div>
    @endif
</x-app-layout>

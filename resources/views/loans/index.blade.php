<x-app-layout>
    <x-slot name="header">
        {{ auth()->user()->hasRole('admin') ? 'Tous les Emprunts' : 'Mes Emprunts' }}
    </x-slot>

    <div class="space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm text-slate-500">
                    {{ $loans instanceof \Illuminate\Pagination\LengthAwarePaginator ? $loans->total() : $loans->count() }} emprunt(s) enregistre(s)
                </p>
                <h1 class="text-2xl font-semibold text-slate-900">
                    {{ auth()->user()->hasRole('admin') ? 'Emprunts' : 'Mes emprunts' }}
                </h1>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <button type="button" class="app-btn app-btn-secondary">
                    <i class="fas fa-download"></i>
                    Exporter
                </button>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <div class="app-card p-5">
                <p class="text-xs text-slate-500 uppercase tracking-widest">Total</p>
                <div class="mt-3 flex items-center justify-between">
                    <p class="text-2xl font-semibold text-slate-900">
                        {{ $loans instanceof \Illuminate\Pagination\LengthAwarePaginator ? $loans->total() : $loans->count() }}
                    </p>
                    <span class="app-badge app-badge-info">
                        <i class="fas fa-hand-holding-heart"></i>Emprunts
                    </span>
                </div>
            </div>
            <div class="app-card p-5">
                <p class="text-xs text-slate-500 uppercase tracking-widest">Actifs</p>
                <div class="mt-3 flex items-center justify-between">
                    <p class="text-2xl font-semibold text-slate-900">{{ $loans->where('status', 'active')->count() }}</p>
                    <span class="app-badge app-badge-success">
                        <i class="fas fa-circle-check"></i>En cours
                    </span>
                </div>
            </div>
            <div class="app-card p-5">
                <p class="text-xs text-slate-500 uppercase tracking-widest">Retards</p>
                <div class="mt-3 flex items-center justify-between">
                    <p class="text-2xl font-semibold text-slate-900">
                        {{ $loans->where('status', 'active')->filter(fn($loan) => $loan->due_date->isPast())->count() }}
                    </p>
                    <span class="app-badge app-badge-danger">
                        <i class="fas fa-triangle-exclamation"></i>En retard
                    </span>
                </div>
            </div>
        </div>

        <div class="app-card p-6">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Historique des emprunts</h2>
                    <p class="text-sm text-slate-500">Consultez les emprunts actifs, retournes ou en retard.</p>
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

            <form method="GET" action="{{ route('loans.index') }}" class="mt-6 grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <div class="lg:col-span-2">
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-widest">Recherche</label>
                    <div class="relative mt-2">
                        <i class="fas fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" name="q" class="app-input pl-10" placeholder="Livre ou membre">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-widest">Statut</label>
                    <select class="app-select mt-2" disabled>
                        <option>Tous</option>
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
                        @if(auth()->user()->hasRole('admin'))
                            <th>Membre</th>
                        @endif
                        <th>Livre</th>
                        <th>Emprunte le</th>
                        <th>Retour prevu</th>
                        <th>Statut</th>
                        <th class="text-right">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($loans as $loan)
                        <tr class="hover:bg-slate-50 transition">
                            @if(auth()->user()->hasRole('admin'))
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 bg-blue-50 rounded-full flex items-center justify-center">
                                            <span class="text-xs text-blue-600 font-bold">{{ strtoupper(substr($loan->user->name, 0, 1)) }}</span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-slate-900">{{ $loan->user->name }}</p>
                                            <p class="text-xs text-slate-500">{{ $loan->user->email ?? '' }}</p>
                                        </div>
                                    </div>
                                </td>
                            @endif
                            <td>
                                <div>
                                    <p class="font-medium text-slate-900">{{ $loan->book->title }}</p>
                                    <p class="text-xs text-slate-500">{{ $loan->book->author->name }}</p>
                                </div>
                            </td>
                            <td class="text-slate-600">{{ $loan->borrowed_at->format('d/m/Y') }}</td>
                            <td class="text-slate-600">
                                <div class="flex flex-col">
                                    <span class="{{ $loan->due_date->isPast() && $loan->status === 'active' ? 'text-rose-600 font-semibold' : '' }}">
                                        {{ $loan->due_date->format('d/m/Y') }}
                                    </span>
                                    @if($loan->due_date->isPast() && $loan->status === 'active')
                                        <span class="text-xs text-rose-500">En retard</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($loan->status === 'active' && $loan->due_date->isPast())
                                    <span class="badge-overdue">En retard</span>
                                @elseif($loan->status === 'active')
                                    <span class="badge-active">Actif</span>
                                @else
                                    <span class="badge-returned">Retourne</span>
                                @endif
                            </td>
                            <td class="text-right">
                                @if($loan->status === 'active')
                                    <form method="POST" action="{{ route('loans.return', $loan) }}" class="inline-flex">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="app-btn app-btn-success">
                                            <i class="fas fa-undo"></i>
                                            Retourner
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-400">{{ $loan->returned_at?->format('d/m/Y') }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-14">
                                <div class="flex flex-col items-center gap-3 text-slate-500">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center">
                                        <i class="fas fa-hand-holding-heart"></i>
                                    </div>
                                    <p class="text-sm">Aucun emprunt enregistre</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($loans instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-500">
                    Affichage de {{ $loans->count() }} emprunt(s) sur {{ $loans->total() }}
                </p>
                <div class="mt-2">{{ $loans->links() }}</div>
            </div>
        @endif
    </div>
</x-app-layout>

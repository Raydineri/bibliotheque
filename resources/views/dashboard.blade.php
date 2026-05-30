<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="font-semibold text-xl text-gray-800">Tableau de bord</h2>
                <p class="text-sm text-gray-500">Vue d'ensemble des performances et activites.</p>
            </div>
            <div class="text-xs text-gray-400">Mise a jour: {{ now()->format('M d, Y') }}</div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-6 gap-4 mb-8">
        <div class="card p-5 bg-gradient-to-br from-blue-50 to-white">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-book text-blue-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total_books'] }}</p>
                    <p class="text-xs text-gray-500">Total Livres</p>
                </div>
            </div>
        </div>
        <div class="card p-5 bg-gradient-to-br from-green-50 to-white">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['available_books'] }}</p>
                    <p class="text-xs text-gray-500">Disponibles</p>
                </div>
            </div>
        </div>
        <div class="card p-5 bg-gradient-to-br from-yellow-50 to-white">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-hand-holding-heart text-yellow-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['active_loans'] }}</p>
                    <p class="text-xs text-gray-500">Emprunts actifs</p>
                </div>
            </div>
        </div>
        <div class="card p-5 bg-gradient-to-br from-red-50 to-white">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 bg-red-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-red-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['overdue_loans'] }}</p>
                    <p class="text-xs text-gray-500">En retard</p>
                </div>
            </div>
        </div>
        <div class="card p-5 bg-gradient-to-br from-purple-50 to-white">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-purple-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total_members'] }}</p>
                    <p class="text-xs text-gray-500">Membres</p>
                </div>
            </div>
        </div>
        <div class="card p-5 bg-gradient-to-br from-indigo-50 to-white">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-history text-indigo-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total_loans'] }}</p>
                    <p class="text-xs text-gray-500">Total emprunts</p>
                </div>
            </div>
        </div>
    </div>

    @if($isAdmin)
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
            <div class="card p-6 xl:col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-700">Activite des emprunts</h3>
                    <span class="text-xs text-gray-400">6 derniers mois</span>
                </div>
                <div class="h-72">
                    <canvas id="loansTrendChart" class="w-full h-full"></canvas>
                </div>
            </div>
            <div class="card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-700">Statut des emprunts</h3>
                    <span class="text-xs text-gray-400">Aujourd'hui</span>
                </div>
                <div class="h-72">
                    <canvas id="statusChart" class="w-full h-full"></canvas>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
            <div class="card p-6 xl:col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-700">Top categories</h3>
                    <span class="text-xs text-gray-400">Top 5</span>
                </div>
                <div class="h-72">
                    <canvas id="categoryChart" class="w-full h-full"></canvas>
                </div>
            </div>
            <div class="card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-700">Nouveaux membres</h3>
                    <span class="text-xs text-gray-400">6 derniers mois</span>
                </div>
                <div class="h-72">
                    <canvas id="membersTrendChart" class="w-full h-full"></canvas>
                </div>
            </div>
        </div>
    @endif

    @if($isMember)
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
            <div class="card p-6 xl:col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-700">Mes emprunts</h3>
                    <span class="text-xs text-gray-400">6 derniers mois</span>
                </div>
                <div class="h-72">
                    <canvas id="userLoansTrendChart" class="w-full h-full"></canvas>
                </div>
            </div>
            <div class="card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-700">Mes statuts</h3>
                    <span class="text-xs text-gray-400">Aujourd'hui</span>
                </div>
                <div class="h-72">
                    <canvas id="userStatusChart" class="w-full h-full"></canvas>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
            <div class="card p-6 xl:col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-700">Mes categories preferees</h3>
                    <span class="text-xs text-gray-400">Top 5</span>
                </div>
                <div class="h-72">
                    <canvas id="userCategoryChart" class="w-full h-full"></canvas>
                </div>
            </div>
            <div class="card p-6 flex flex-col justify-between">
                <div>
                    <h3 class="font-semibold text-gray-700 mb-2">Resume personnel</h3>
                    <p class="text-sm text-gray-500">Gardez un oeil sur vos emprunts en cours.</p>
                </div>
                <div class="mt-6">
                    <div class="flex items-center justify-between text-sm text-gray-600">
                        <span>Emprunts actifs</span>
                        <span class="font-semibold text-gray-800">{{ $myLoans->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm text-gray-600 mt-2">
                        <span>En retard</span>
                        <span class="font-semibold text-red-600">{{ $myLoans->where('due_date', '<', now())->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card p-6">
            <h3 class="font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <i class="fas fa-fire text-orange-500"></i> Livres les plus empruntes
            </h3>
            @forelse($mostBorrowed as $book)
                <div class="flex items-center justify-between py-3 border-b last:border-0">
                    <div>
                        <p class="font-medium text-sm text-gray-800">{{ $book->title }}</p>
                        <p class="text-xs text-gray-400">{{ $book->author->name ?? '' }}</p>
                    </div>
                    <span class="bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full font-medium">
                        {{ $book->loans_count }} emprunts
                    </span>
                </div>
            @empty
                <p class="text-gray-400 text-sm text-center py-4">Aucun emprunt enregistre</p>
            @endforelse
        </div>

        @if(auth()->user()->hasRole('admin') && $overdueLoans->count())
            <div class="card p-6">
                <h3 class="font-semibold text-gray-700 mb-4 flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle text-red-500"></i>
                    Emprunts en retard ({{ $overdueLoans->count() }})
                </h3>
                @foreach($overdueLoans as $loan)
                    <div class="flex items-center justify-between py-3 border-b last:border-0">
                        <div>
                            <p class="font-medium text-sm text-gray-800">{{ $loan->book->title }}</p>
                            <p class="text-xs text-gray-400">{{ $loan->user->name }}</p>
                        </div>
                        <span class="badge-overdue">
                            {{ $loan->due_date->diffForHumans() }}
                        </span>
                    </div>
                @endforeach
            </div>
        @else
            <div class="card p-6">
                <h3 class="font-semibold text-gray-700 mb-4 flex items-center gap-2">
                    <i class="fas fa-book-reader text-blue-500"></i> Mes emprunts actifs
                </h3>
                @forelse($myLoans as $loan)
                    <div class="flex items-center justify-between py-3 border-b last:border-0">
                        <div>
                            <p class="font-medium text-sm text-gray-800">{{ $loan->book->title }}</p>
                            <p class="text-xs text-gray-400">Retour avant le {{ $loan->due_date->format('d/m/Y') }}</p>
                        </div>
                        @if($loan->due_date->isPast())
                            <span class="badge-overdue">En retard</span>
                        @else
                            <span class="badge-active">Actif</span>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-400 text-sm text-center py-4">Aucun emprunt actif</p>
                @endforelse
                <div class="mt-4">
                    <a href="{{ route('books.index') }}"
                       class="block text-center bg-blue-600 text-white py-2 rounded-xl text-sm hover:bg-blue-700 transition">
                        <i class="fas fa-search mr-1"></i> Parcourir le catalogue
                    </a>
                </div>
            </div>
        @endif
    </div>

    <script id="dashboard-charts-data" type="application/json">
        @json($chartData)
    </script>
</x-app-layout>

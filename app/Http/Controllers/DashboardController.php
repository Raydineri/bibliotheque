<?php
namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\User;
use App\Services\BookService;
use App\Services\LoanService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct(
        private BookService $bookService,
        private LoanService $loanService
    ) {}

    public function index()
    {
        $user     = auth()->user();
        $isAdmin  = $user->hasRole('admin');
        $isUser   = $user->hasRole('user');
        $stats    = $this->bookService->getStats();

        $mostBorrowed = $this->bookService->getMostBorrowedBooks(5);
        $overdueLoans = $isAdmin
            ? $this->loanService->getOverdueLoans()
            : collect();
        $myLoans = $isUser
            ? $this->loanService->getUserLoans($user)->where('status', 'active')
            : collect();

        $chartData = $this->buildChartData($user, $isUser);

        return view('dashboard', compact('stats', 'mostBorrowed', 'overdueLoans', 'myLoans', 'chartData', 'isAdmin', 'isUser'));
    }

    private function buildChartData(User $user, bool $isUser): array
    {
        $months      = 6;
        $monthLabels = $this->buildMonthLabels($months);

        $adminStatus  = $this->getLoanStatusCounts();
        $userStatus   = $isUser ? $this->getLoanStatusCounts($user->id) : [0, 0, 0];

        $topCategories     = $this->getTopCategories();
        $userTopCategories = $isUser ? $this->getTopCategories($user->id) : collect();

        return [
            'months' => $monthLabels,
            'admin'  => [
                'loansPerMonth'   => $this->buildMonthlySeries($months, function (Carbon $start, Carbon $end) {
                    return Loan::whereBetween('borrowed_at', [$start, $end])->count();
                }),
                'membersPerMonth' => $this->buildMonthlySeries($months, function (Carbon $start, Carbon $end) {
                    return User::whereHas('roles', function ($query) {
                        $query->where('name', 'user');
                    })->whereBetween('created_at', [$start, $end])->count();
                }),
                'categoryLabels'  => $topCategories->pluck('name')->values()->all(),
                'categoryTotals'  => $topCategories->pluck('total')->values()->all(),
                'loanStatus'      => [
                    'labels' => ['Active', 'Overdue', 'Returned'],
                    'values' => $adminStatus,
                ],
            ],
            'user'   => [
                'loansPerMonth'  => $isUser
                    ? $this->buildMonthlySeries($months, function (Carbon $start, Carbon $end) use ($user) {
                        return Loan::where('user_id', $user->id)
                            ->whereBetween('borrowed_at', [$start, $end])
                            ->count();
                    })
                    : [],
                'categoryLabels' => $userTopCategories->pluck('name')->values()->all(),
                'categoryTotals' => $userTopCategories->pluck('total')->values()->all(),
                'loanStatus'     => [
                    'labels' => ['Active', 'Overdue', 'Returned'],
                    'values' => $userStatus,
                ],
            ],
        ];
    }

    private function buildMonthLabels(int $months): array
    {
        $labels = [];
        $cursor = now()->subMonths($months - 1)->startOfMonth();

        for ($i = 0; $i < $months; $i++) {
            $labels[] = $cursor->format('M Y');
            $cursor->addMonth();
        }

        return $labels;
    }

    private function buildMonthlySeries(int $months, callable $counter): array
    {
        $series = [];
        $cursor = now()->subMonths($months - 1)->startOfMonth();

        for ($i = 0; $i < $months; $i++) {
            $start = $cursor->copy()->startOfMonth();
            $end   = $cursor->copy()->endOfMonth();

            $series[] = $counter($start, $end);
            $cursor->addMonth();
        }

        return $series;
    }

    private function getTopCategories(?int $userId = null, int $limit = 5)
    {
        $query = DB::table('loans')
            ->join('books', 'books.id', '=', 'loans.book_id')
            ->join('categories', 'categories.id', '=', 'books.category_id')
            ->select('categories.name', DB::raw('count(*) as total'))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total')
            ->limit($limit);

        if ($userId) {
            $query->where('loans.user_id', $userId);
        }

        return $query->get();
    }

    private function getLoanStatusCounts(?int $userId = null): array
    {
        $base = Loan::query();

        if ($userId) {
            $base->where('user_id', $userId);
        }

        $overdue  = (clone $base)->where('status', 'active')->where('due_date', '<', now())->count();
        $active   = (clone $base)->where('status', 'active')->where('due_date', '>=', now())->count();
        $returned = (clone $base)->where('status', 'returned')->count();

        return [$active, $overdue, $returned];
    }
}

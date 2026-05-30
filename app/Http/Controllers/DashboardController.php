<?php
namespace App\Http\Controllers;

use App\Services\BookService;
use App\Services\LoanService;

class DashboardController extends Controller
{
    public function __construct(
        private BookService $bookService,
        private LoanService $loanService
    ) {}

    public function index()
    {
        $stats         = $this->bookService->getStats();
        $mostBorrowed  = $this->bookService->getMostBorrowedBooks(5);
        $overdueLoans  = auth()->user()->hasRole('admin')
            ? $this->loanService->getOverdueLoans()
            : collect();
        $myLoans       = auth()->user()->hasRole('member')
            ? $this->loanService->getUserLoans(auth()->user())->where('status', 'active')
            : collect();

        return view('dashboard', compact('stats', 'mostBorrowed', 'overdueLoans', 'myLoans'));
    }
}

<?php
namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Services\LoanService;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function __construct(private LoanService $loanService) {}

    public function index()
    {
        $loans = auth()->user()->hasRole('admin')
            ? Loan::with(['user', 'book'])->orderByDesc('created_at')->paginate(15)
            : $this->loanService->getUserLoans(auth()->user());

        return view('loans.index', compact('loans'));
    }

    public function store(Request $request)
    {
        $request->validate(['book_id' => 'required|exists:books,id']);

        $book   = Book::findOrFail($request->book_id);
        $result = $this->loanService->borrowBook(auth()->user(), $book);

        if (is_string($result)) {
            return back()->with('error', $result);
        }

        return back()->with('success', 'Emprunt enregistré ! Retour avant le '
            . $result->due_date->format('d/m/Y'));
    }

    public function returnBook(Loan $loan)
    {
        $this->loanService->returnBook($loan);
        return back()->with('success', 'Retour enregistré avec succès !');
    }
}

<?php
namespace App\Services;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;

class LoanService
{
    public function borrowBook(User $user, Book $book): Loan|string
    {
        if ($book->available_copies <= 0) {
            return "Ce livre n'est plus disponible.";
        }

        $loan = Loan::create([
            'user_id'     => $user->id,
            'book_id'     => $book->id,
            'borrowed_at' => Carbon::today(),
            'due_date'    => Carbon::today()->addDays(14),
            'status'      => 'active',
        ]);

        $book->decrement('available_copies');
        return $loan;
    }

    public function returnBook(Loan $loan): void
    {
        $loan->update([
            'returned_at' => Carbon::today(),
            'status'      => 'returned',
        ]);
        $loan->book->increment('available_copies');
    }

    public function getUserLoans(User $user)
    {
        return Loan::with('book.author')
            ->where('user_id', $user->id)
            ->orderByDesc('borrowed_at')
            ->get();
    }

    public function getOverdueLoans()
    {
        return Loan::with(['user', 'book'])
            ->overdue()
            ->get();
    }
}

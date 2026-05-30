<?php
namespace App\Services;

use App\Models\Book;

class BookService
{
    public function getAvailableBooks()
    {
        return Book::with(['author', 'category'])
            ->available()
            ->get();
    }

    public function getMostBorrowedBooks(int $limit = 5)
    {
        return Book::withCount('loans')
            ->orderByDesc('loans_count')
            ->limit($limit)
            ->get();
    }

    public function searchBooks(string $query)
    {
        return Book::with(['author', 'category'])
            ->where('title', 'ilike', "%{$query}%")
            ->orWhereHas('author', fn($q) => $q->where('name', 'ilike', "%{$query}%"))
            ->get();
    }

    public function getStats(): array
    {
        return [
            'total_books'       => Book::count(),
            'available_books'   => Book::available()->count(),
            'total_loans'       => \App\Models\Loan::count(),
            'active_loans'      => \App\Models\Loan::where('status', 'active')->count(),
            'overdue_loans'     => \App\Models\Loan::overdue()->count(),
            'total_members'     => \App\Models\User::whereHas('roles', function ($query) {
                $query->where('name', 'member');
            })->count(),
        ];
    }
}

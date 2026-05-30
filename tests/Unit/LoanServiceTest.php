<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use App\Models\Loan;
use App\Services\LoanService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoanServiceTest extends TestCase
{
    use RefreshDatabase;

    private LoanService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new LoanService();
    }

    public function test_borrow_book_creates_loan_and_decrements_stock(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create(['available_copies' => 2]);

        $loan = $this->service->borrowBook($user, $book);

        $this->assertInstanceOf(Loan::class, $loan);
        $this->assertDatabaseHas('loans', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status'  => 'active',
        ]);
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'available_copies' => 1,
        ]);
    }

    public function test_borrow_book_returns_error_when_unavailable(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create(['available_copies' => 0]);

        $result = $this->service->borrowBook($user, $book);

        $this->assertIsString($result);
        $this->assertSame("Ce livre n'est plus disponible.", $result);
        $this->assertDatabaseCount('loans', 0);
    }

    public function test_return_book_marks_returned_and_increments_stock(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create(['available_copies' => 0]);

        $loan = Loan::factory()->create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status'  => 'active',
        ]);

        $this->service->returnBook($loan);

        $this->assertDatabaseHas('loans', [
            'id' => $loan->id,
            'status' => 'returned',
        ]);
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'available_copies' => 1,
        ]);
    }
}

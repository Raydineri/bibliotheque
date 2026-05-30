<?php
namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Book;
use App\Services\BookService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookServiceTest extends TestCase
{
    use RefreshDatabase;

    private BookService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new BookService();
    }

    public function test_returns_only_available_books(): void
    {
        Book::factory()->count(3)->create(['available_copies' => 0]);
        Book::factory()->count(2)->create(['available_copies' => 2]);

        $result = $this->service->getAvailableBooks();

        $this->assertCount(2, $result);
    }

    public function test_get_most_borrowed_books(): void
    {
        Book::factory()->count(5)->create();

        $result = $this->service->getMostBorrowedBooks(3);

        $this->assertCount(3, $result);
    }

    public function test_stats_returns_correct_keys(): void
    {
        $stats = $this->service->getStats();

        $this->assertArrayHasKey('total_books', $stats);
        $this->assertArrayHasKey('active_loans', $stats);
        $this->assertArrayHasKey('overdue_loans', $stats);
    }
}

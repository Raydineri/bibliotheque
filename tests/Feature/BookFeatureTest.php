<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class BookFeatureTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $member;

    protected function setUp(): void
    {
        parent::setUp();
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'member']);

        $this->admin  = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->member = User::factory()->create();
        $this->member->assignRole('member');
    }

    public function test_books_list_is_accessible(): void
    {
        $this->actingAs($this->member)
            ->get('/books')
            ->assertStatus(200);
    }

    public function test_admin_can_create_book(): void
    {
        $author   = \App\Models\Author::factory()->create();
        $category = \App\Models\Category::factory()->create();

        $this->actingAs($this->admin)
            ->post('/books', [
                'title'          => 'Laravel pour les pros',
                'total_copies'   => 3,
                'author_id'      => $author->id,
                'category_id'    => $category->id,
            ])
            ->assertRedirect('/books');

        $this->assertDatabaseHas('books', ['title' => 'Laravel pour les pros']);
    }

    public function test_member_cannot_access_author_management(): void
    {
        $this->actingAs($this->member)
            ->get('/authors')
            ->assertStatus(403);
    }

    public function test_unauthenticated_user_is_redirected(): void
    {
        $this->get('/books')->assertRedirect('/login');
    }
}

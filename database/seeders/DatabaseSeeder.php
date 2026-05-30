<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Author;
use App\Models\Category;
use App\Models\Book;
use App\Models\Loan;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Créer les rôles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'member']);

        // Admin
        $admin = User::factory()->create([
            'name'         => 'Admin Biblio',
            'email'        => 'admin@biblio.com',
            'password'     => bcrypt('password'),
            'member_since' => now(),
        ]);
        $admin->assignRole('admin');

        // Membres
        $members = User::factory(10)->create([
            'member_since' => now()->subMonths(rand(1, 12)),
        ]);
        $members->each(fn($u) => $u->assignRole('member'));

        // Catégories & Auteurs & Livres
        $categories = Category::factory(6)->create();
        $authors    = Author::factory(15)->create();

        $books = Book::factory(30)->create([
            'author_id'   => fn() => $authors->random()->id,
            'category_id' => fn() => $categories->random()->id,
        ]);

        // Emprunts
        $members->each(function ($member) use ($books) {
            $booksToLoan = $books->where('available_copies', '>', 0)->random(rand(1, 3));
            foreach ($booksToLoan as $book) {
                Loan::create([
                    'user_id'     => $member->id,
                    'book_id'     => $book->id,
                    'borrowed_at' => now()->subDays(rand(1, 30)),
                    'due_date'    => now()->addDays(rand(-5, 14)),
                    'status'      => rand(0, 1) ? 'active' : 'returned',
                ]);
                $book->decrement('available_copies');
            }
        });
    }
}

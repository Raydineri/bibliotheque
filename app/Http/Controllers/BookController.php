<?php
namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\Category;
use App\Services\BookService;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function __construct(private BookService $bookService) {}

    public function index(Request $request)
    {
        $query = Book::with(['author', 'category']);

        if ($request->search) {
            $query->where('title', 'ilike', "%{$request->search}%");
        }
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $books      = $query->paginate(12);
        $categories = Category::all();
        return view('books.index', compact('books', 'categories'));
    }

    public function create()
    {
        $authors    = Author::all();
        $categories = Category::all();
        return view('books.create', compact('authors', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'isbn'           => 'nullable|unique:books',
            'description'    => 'nullable|string',
            'total_copies'   => 'required|integer|min:1',
            'published_year' => 'nullable|integer',
            'author_id'      => 'required|exists:authors,id',
            'category_id'    => 'required|exists:categories,id',
        ]);

        $validated['available_copies'] = $validated['total_copies'];
        Book::create($validated);

        return redirect()->route('books.index')
            ->with('success', 'Livre ajouté avec succès !');
    }

    public function show(Book $book)
    {
        $book->load(['author', 'category', 'loans.user']);
        return view('books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        $authors    = Author::all();
        $categories = Category::all();
        return view('books.edit', compact('book', 'authors', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'total_copies'   => 'required|integer|min:1',
            'published_year' => 'nullable|integer',
            'author_id'      => 'required|exists:authors,id',
            'category_id'    => 'required|exists:categories,id',
        ]);

        $book->update($validated);
        return redirect()->route('books.index')
            ->with('success', 'Livre modifié avec succès !');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('books.index')
            ->with('success', 'Livre supprimé.');
    }
}

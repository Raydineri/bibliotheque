<?php
namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::withCount('books')->paginate(15);
        return view('authors.index', compact('authors'));
    }

    public function create()
    {
        return view('authors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        Author::create($request->only(['name', 'nationality', 'bio']));
        return redirect()->route('admin.authors.index')->with('success', 'Auteur ajouté !');
    }

    public function edit(Author $author)
    {
        return view('authors.edit', compact('author'));
    }

    public function show(Author $author)
    {
        $author->loadCount('books')->load(['books:id,title,author_id']);
        return view('authors.show', compact('author'));
    }

    public function update(Request $request, Author $author)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $author->update($request->only(['name', 'nationality', 'bio']));
        return redirect()->route('admin.authors.index')->with('success', 'Auteur modifié !');
    }

    public function destroy(Author $author)
    {
        $author->delete();
        return redirect()->route('admin.authors.index')->with('success', 'Auteur supprimé.');
    }
}

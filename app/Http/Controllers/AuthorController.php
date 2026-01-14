<?php

namespace App\Http\Controllers;


use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    // Display a listing of the authors filtered and sorted.
    public function index(Request $request)
    {
        $sort = $request->query('sort', 'name');
        $direction = $request->query('direction', 'asc');
        $validSorts = ['name'];
        $validDirections = ['asc', 'desc'];
        $sort = in_array($sort, $validSorts) ? $sort : 'name';
        $direction = in_array($direction, $validDirections) ? $direction : 'asc';

        $authors = Author::orderBy($sort, $direction)->paginate(10)->appends(['sort' => $sort, 'direction' => $direction]);
        return view('authors.index', compact('authors', 'sort', 'direction'));
    }

    /**
     * Show the form for creating a new author.
     */
    public function create()
    {
        return view('authors.create');
    }

    /**
     * Store a newly created author in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:authors,name',
            'picture' => 'nullable|image|max:2048',
        ]);

        $author = new Author();
        $author->name = $validated['name'];
        if ($request->hasFile('picture')) {
            $author->picture = $request->file('picture')->store('authors', 'public');
        }
        $author->save();

        return redirect()->route('authors.index')->with('success', 'Author created successfully.');
    }

    /**
     * Display the specified author.
     */
    public function show(Author $author)
    {
        return view('authors.show', compact('author'));
    }

    /**
     * Show the form for editing the specified author.
     */
    public function edit(Author $author)
    {
        return view('authors.edit', compact('author'));
    }

    /**
     * Update the specified author in storage.
     */
    public function update(Request $request, Author $author)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:authors,name,' . $author->id,
            'picture' => 'nullable|image|max:2048',
        ]);

        $author->name = $validated['name'];
        if ($request->hasFile('picture')) {
            $author->picture = $request->file('picture')->store('authors', 'public');
        }
        $author->save();

        return redirect()->route('authors.index')->with('success', 'Author updated successfully.');
    }

    /**
     * Remove the specified author from storage.
     */
    public function destroy(Author $author)
    {
        $author->delete();
        return redirect()->route('authors.index')->with('success', 'Author deleted successfully.');
    }
}

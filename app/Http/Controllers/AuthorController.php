<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Services\Authors\AuthorService;
use App\Services\Authors\AuthorQueryService;
use App\Http\Requests\Authors\StoreAuthorRequest;
use App\Http\Requests\Authors\UpdateAuthorRequest;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    // Display a listing of the authors filtered and sorted.
    public function index(Request $request, AuthorQueryService $authorQueryService)
    {
        $params = [
            'sort' => $request->query('sort', 'name'),
            'direction' => $request->query('direction', 'asc'),
            'search' => $request->query('search'),
        ];
        $authors = $authorQueryService->getFilteredAuthors($params)
            ->paginate(10)
            ->appends($request->except('page'));
        $sort = $params['sort'];
        $direction = $params['direction'];
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
    public function store(StoreAuthorRequest $request, AuthorService $authorService)
    {
        $validated = $request->validated();
        $picture = $request->file('picture');
        $authorService->create($validated, $picture);
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
    public function update(UpdateAuthorRequest $request, Author $author, AuthorService $authorService)
    {
        $validated = $request->validated();
        $picture = $request->file('picture');
        $authorService->update($author, $validated, $picture);
        return redirect()->route('authors.index')->with('success', 'Author updated successfully.');
    }

    /**
     * Remove the specified author from storage.
     */
    public function destroy(Author $author, AuthorService $authorService)
    {
        $authorService->delete($author);
        return redirect()->route('authors.index')->with('success', 'Author deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers;


use App\Models\Book;
use App\Models\Publisher;
use App\Models\Author;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the books.
     */
    public function index()
    {
        $books = Book::with(['publisher', 'authors'])->orderBy('name')->paginate(10);
        return view('books.index', compact('books'));
    }

    /**
     * Show the form for creating a new book.
     */
    public function create()
    {
        $publishers = Publisher::orderBy('name')->get();
        $authors = Author::orderBy('name')->get();
        return view('books.create', compact('publishers', 'authors'));
    }

    /**
     * Store a newly created book in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'isbn' => 'required|string|max:255|unique:books,isbn',
            'name' => 'required|string|max:255',
            'bibliography' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048',
            'price' => 'nullable|numeric|min:0',
            'publisher_id' => 'required|exists:publishers,id',
            'authors' => 'required|array',
            'authors.*' => 'exists:authors,id',
        ]);

        $book = new Book();
        $book->fill($validated);
        if ($request->hasFile('cover_image')) {
            $book->cover_image = $request->file('cover_image')->store('books', 'public');
        }
        $book->save();
        $book->authors()->sync($validated['authors']);

        return redirect()->route('books.index')->with('success', 'Book created successfully.');
    }

    /**
     * Display the specified book.
     */
    public function show(Book $book)
    {
        $book->load(['publisher', 'authors']);
        return view('books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified book.
     */
    public function edit(Book $book)
    {
        $publishers = Publisher::orderBy('name')->get();
        $authors = Author::orderBy('name')->get();
        $book->load('authors');
        return view('books.edit', compact('book', 'publishers', 'authors'));
    }

    /**
     * Update the specified book in storage.
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'isbn' => 'required|string|max:255|unique:books,isbn,' . $book->id,
            'name' => 'required|string|max:255',
            'bibliography' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048',
            'price' => 'nullable|numeric|min:0',
            'publisher_id' => 'required|exists:publishers,id',
            'authors' => 'required|array',
            'authors.*' => 'exists:authors,id',
        ]);

        $book->fill($validated);
        if ($request->hasFile('cover_image')) {
            $book->cover_image = $request->file('cover_image')->store('books', 'public');
        }
        $book->save();
        $book->authors()->sync($validated['authors']);

        return redirect()->route('books.index')->with('success', 'Book updated successfully.');
    }

    /**
     * Remove the specified book from storage.
     */
    public function destroy(Book $book)
    {
        $book->authors()->detach();
        $book->delete();
        return redirect()->route('books.index')->with('success', 'Book deleted successfully.');
    }
    /**
     * Export books as CSV file.
     */
    public function exportCsv()
    {
        $books = Book::with(['publisher', 'authors'])->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="books.csv"',
        ];

        $callback = function() use ($books) {
            $handle = fopen('php://output', 'w');
            // Header row
            fputcsv($handle, ['ID', 'ISBN', 'Name', 'Publisher', 'Authors', 'Price']);
            foreach ($books as $book) {
                fputcsv($handle, [
                    $book->id,
                    $book->isbn,
                    $book->name,
                    $book->publisher ? $book->publisher->name : '',
                    $book->authors->pluck('name')->join(', '),
                    $book->price,
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}

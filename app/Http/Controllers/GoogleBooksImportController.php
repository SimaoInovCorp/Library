<?php

namespace App\Http\Controllers;

use App\Services\GoogleBooks\GoogleBooksService;
use App\Services\GoogleBooks\Exceptions\GoogleBooksApiException;
use App\Services\Books\BookService;
use App\Services\GoogleBooks\GoogleBooksTransformer;
use App\Services\GoogleBooks\GoogleBookDataFactory;
use Illuminate\Http\Request;

class GoogleBooksImportController extends Controller
{
    public function __construct(
        private readonly GoogleBooksService $googleBooksService,
        private readonly BookService $bookService,
        private readonly GoogleBooksTransformer $googleBooksTransformer,
    ) {}

    /**
     * Show the import form
     */
    public function index()
    {
        return view('books.import-google');
    }

    /**
     * Search Google Books API
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2',
        ]);

        try {
            $results = $this->googleBooksService->searchBooks($request->input('query'), 20);
            $books = $this->googleBooksTransformer->transformForView($results);

            if (empty($books)) {
                return back()->with('error', 'No books found with valid ISBN. Try a different search.');
            }

            return view('books.import-google', [
                'searchQuery' => $request->input('query'),
                'searchResults' => $books,
            ]);

        } catch (GoogleBooksApiException $e) {
            return back()->with('error', 'Failed to search Google Books: ' . $e->getMessage());
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Import a book from Google Books
     */
    public function import(Request $request)
    {
        $request->validate([
            'volume_id' => 'required|string',
            'isbn' => 'nullable|string',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'authors' => 'nullable|array',
            'publisher' => 'nullable|string',
            'published_date' => 'nullable|string',
            'thumbnail_url' => 'nullable|string',
            'price' => 'nullable|numeric',
            'currency_code' => 'nullable|string',
            'page_count' => 'nullable|integer',
            'language' => 'nullable|string',
            'copies' => 'required|integer|min:1|max:5',
        ]);

        try {
            // Check if book already exists
            if ($request->isbn && $this->bookService->bookExistsByIsbn($request->isbn)) {
                return back()->with('error', "Book '{$request->title}' already exists in your library.");
            }

            // Create GoogleBookData from request using factory
            $googleBook = GoogleBookDataFactory::fromRequest($request);

            // Import the book
            $book = $this->bookService->createFromGoogleBooks($googleBook, $request->copies);

            return back()->with('success', "Successfully imported '{$book->name}' with {$request->copies} " .
                    str('copy')->plural($request->copies) . "!");

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to import book: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Services\Books\BookQueryService;
use App\Services\Books\BookService;
use App\Services\Books\BookExportService;
use App\Services\Books\BookFormService;
use App\Services\ErrorHandlingService;
use App\Http\Requests\Books\StoreBookRequest;
use App\Http\Requests\Books\UpdateBookRequest;
use Illuminate\Routing\Controller as BaseController;

class BookController extends BaseController
{
        public function __construct()
        {
            $this->middleware(['auth', 'admin']);
        }
    /**
     * Display a listing of the books.
     */
    public function index(Request $request, BookQueryService $bookQueryService)
    {
        $params = [
            'sort' => $request->query('sort', 'name'),
            'direction' => $request->query('direction', 'asc'),
            'search' => $request->query('search'),
        ];
        $books = $bookQueryService->getFilteredBooks($params)
            ->paginate(10)
            ->appends($request->except('page'));
        $sort = $params['sort'];
        $direction = $params['direction'];
        return view('books.index', compact('books', 'sort', 'direction'));
    }

    /**
     * Show the form for creating a new book.
     */
    public function create(BookFormService $formService)
    {
        return view('books.create', $formService->getFormData());
    }

    /**
     * Store a newly created book in storage.
     */
    public function store(StoreBookRequest $request, BookService $bookService, ErrorHandlingService $errorService)
    {
        try {
            $validated = $request->validated();
            $coverImage = $request->file('cover_image');
            $bookService->create($validated, $coverImage);
            return redirect()->route('books.index')->with('success', 'Book created successfully.');
        } catch (\Exception $e) {
            return $errorService->handle($e, 'Book creation failed');
        }
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
    public function edit(Book $book, BookFormService $formService)
    {
        $book->load('authors');
        return view('books.edit', array_merge(['book' => $book], $formService->getFormData()));
    }

    /**
     * Update the specified book in storage.
     */
    public function update(UpdateBookRequest $request, Book $book, BookService $bookService, ErrorHandlingService $errorService)
    {
        try {
            $validated = $request->validated();
            $coverImage = $request->file('cover_image');
            $bookService->update($book, $validated, $coverImage);
            return redirect()->route('books.index')->with('success', 'Book updated successfully.');
        } catch (\Exception $e) {
            return $errorService->handle($e, 'Book update failed');
        }
    }

    /**
     * Remove the specified book from storage.
     */
    public function destroy(Book $book, BookService $bookService, ErrorHandlingService $errorService)
    {
        try {
            $bookService->delete($book);
            return redirect()->route('books.index')->with('success', 'Book deleted successfully.');
        } catch (\Exception $e) {
            return $errorService->handle($e, 'Book deletion failed');
        }
    }
    /**
     * Export books as CSV file.
     */
    public function exportCsv(BookExportService $bookExportService, ErrorHandlingService $errorService)
    {
        try {
            return $bookExportService->exportCsv();
        } catch (\Exception $e) {
            return $errorService->handle($e, 'Book CSV export failed');
        }
    }
}

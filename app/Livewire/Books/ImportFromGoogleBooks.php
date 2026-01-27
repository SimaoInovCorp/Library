<?php

namespace App\Livewire\Books;

use App\Services\GoogleBooks\GoogleBooksService;
use App\Services\GoogleBooks\Exceptions\GoogleBooksApiException;
use App\Services\Books\BookService;
use App\DataTransferObjects\GoogleBookData;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layout')]
#[Title('Import from Google Books')]
class ImportFromGoogleBooks extends Component
{
    #[Validate('required|string|min:2')]
    public string $searchQuery = '';

    #[Validate('required|integer|min:1|max:5')]
    public int $copies = 1;

    public array $searchResults = [];
    public bool $searching = false;
    public ?string $errorMessage = null;
    public ?string $successMessage = null;

    /**
     * Search for books in Google Books API
     */
    public function search()
    {
        $this->validate();

        $this->searching = true;
        $this->searchResults = [];
        $this->errorMessage = null;
        $this->successMessage = null;

        try {
            $googleBooksService = app(GoogleBooksService::class);
            $results = $googleBooksService->searchBooks($this->searchQuery, 20);

            $this->searchResults = $results
                ->filter(fn($book) => $book->hasIsbn()) // Only show books with ISBN
                ->map(fn($book) => $book->toArray())
                ->values()
                ->toArray();

            if (empty($this->searchResults)) {
                $this->errorMessage = 'No books found with valid ISBN. Try a different search.';
            }
        } catch (GoogleBooksApiException $e) {
            $this->errorMessage = 'Failed to search Google Books: ' . $e->getMessage();
        } catch (\Exception $e) {
            $this->errorMessage = 'An error occurred: ' . $e->getMessage();
        } finally {
            $this->searching = false;
        }
    }

    /**
     * Import a book from Google Books
     */
    public function import(string $volumeId)
    {
        $this->errorMessage = null;
        $this->successMessage = null;

        try {
            // Find the book data in search results
            $bookData = collect($this->searchResults)
                ->firstWhere('volume_id', $volumeId);

            if (!$bookData) {
                $this->errorMessage = 'Book not found in search results.';
                return;
            }

            // Recreate GoogleBookData from array
            $googleBook = new GoogleBookData(
                volumeId: $bookData['volume_id'],
                isbn13: $bookData['isbn'] ?? null,
                isbn10: null,
                title: $bookData['title'],
                description: $bookData['description'],
                authors: $bookData['authors'],
                publisher: $bookData['publisher'],
                publishedDate: $bookData['published_date'],
                thumbnailUrl: $bookData['thumbnail_url'],
                price: $bookData['price'],
                currencyCode: $bookData['currency_code'],
                pageCount: $bookData['page_count'],
                language: $bookData['language'],
            );

            // Check if book already exists
            $bookService = app(BookService::class);
            if ($googleBook->hasIsbn() && $bookService->bookExistsByIsbn($googleBook->getPreferredIsbn())) {
                $this->errorMessage = "Book '{$googleBook->title}' already exists in your library.";
                return;
            }

            // Import the book
            $book = $bookService->createFromGoogleBooks($googleBook, $this->copies);

            $this->successMessage = "Successfully imported '{$book->name}' with {$this->copies} " .
                                   str('copy')->plural($this->copies) . "!";

            // Remove from search results
            $this->searchResults = collect($this->searchResults)
                ->reject(fn($item) => $item['volume_id'] === $volumeId)
                ->values()
                ->toArray();

        } catch (\Exception $e) {
            $this->errorMessage = 'Failed to import book: ' . $e->getMessage();
        }
    }

    /**
     * Clear search results
     */
    public function clearSearch()
    {
        $this->searchQuery = '';
        $this->searchResults = [];
        $this->errorMessage = null;
        $this->successMessage = null;
    }

    public function render()
    {
        return view('livewire.books.import-from-google-books');
    }
}

<?php

namespace App\Services\Books;

use App\Models\Book;
use App\Models\Author;
use App\Models\Publisher;
use App\DataTransferObjects\GoogleBookData;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class BookService
{
    /**
     * Create a new book with validated data and handle file upload and author sync.
     */
    public function create(array $validated, ?UploadedFile $coverImage = null): Book
    {
        $book = new Book();
        $book->fill($validated);
        if (isset($validated['copies'])) {
            $book->copies = $validated['copies'];
        }
        if ($coverImage) {
            $book->cover_image = $coverImage->store('books', 'public');
        }
        $book->save();
        $book->authors()->sync($validated['authors'] ?? []);
        return $book;
    }

    /**
     * Update an existing book with validated data and handle file upload and author sync.
     */
    public function update(Book $book, array $validated, ?UploadedFile $coverImage = null): Book
    {
        $book->fill($validated);
        if (isset($validated['copies'])) {
            $book->copies = $validated['copies'];
        }
        if ($coverImage) {
            $book->cover_image = $coverImage->store('books', 'public');
        }
        $book->save();
        $book->authors()->sync($validated['authors'] ?? []);
        return $book;
    }

    /**
     * Delete a book and detach its authors.
     */
    public function delete(Book $book): void
    {
        $book->authors()->detach();
        $book->delete();
    }

    /**
     * Create a book from Google Books API data
     *
     * @param GoogleBookData $googleBook
     * @param int $copies Number of copies to add (default: 1)
     * @return Book
     * @throws \Exception
     */
    public function createFromGoogleBooks(GoogleBookData $googleBook, int $copies = 1): Book
    {
        return DB::transaction(function () use ($googleBook, $copies) {
            // Check if book with ISBN already exists
            if ($googleBook->hasIsbn()) {
                $existingBook = Book::where('isbn', $googleBook->getPreferredIsbn())->first();
                if ($existingBook) {
                    throw new \Exception("Book with ISBN {$googleBook->getPreferredIsbn()} already exists.");
                }
            }

            // Find or create publisher
            $publisher = null;
            if ($googleBook->publisher) {
                $publisher = Publisher::firstOrCreate(
                    ['name' => $googleBook->publisher]
                );
            } else {
                // Create a default publisher if none exists
                $publisher = Publisher::firstOrCreate(
                    ['name' => 'Unknown Publisher']
                );
            }

            // Download and store cover image if available
            $coverImagePath = null;
            if ($googleBook->thumbnailUrl) {
                $coverImagePath = $this->downloadCoverImage($googleBook->thumbnailUrl, $googleBook->volumeId);
            }

            // Create the book
            $book = Book::create([
                'isbn' => $googleBook->getPreferredIsbn() ?? 'N/A-' . $googleBook->volumeId,
                'name' => $googleBook->title,
                'bibliography' => $googleBook->description,
                'cover_image' => $coverImagePath,
                'price' => $googleBook->price,
                'publisher_id' => $publisher->id,
                'copies' => $copies,
            ]);

            // Find or create authors and attach them
            if (!empty($googleBook->authors)) {
                $authorIds = [];
                foreach ($googleBook->authors as $authorName) {
                    $author = Author::firstOrCreate(
                        ['name' => $authorName]
                    );
                    $authorIds[] = $author->id;
                }
                $book->authors()->sync($authorIds);
            }

            return $book;
        });
    }

    /**
     * Download and store cover image from URL
     *
     * @param string $url
     * @param string $volumeId
     * @return string|null Path to stored image
     */
    private function downloadCoverImage(string $url, string $volumeId): ?string
    {
        try {
            // Use HTTPS for thumbnail URLs
            $url = str_replace('http://', 'https://', $url);

            $response = Http::timeout(10)->get($url);

            if ($response->successful()) {
                $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
                $extension = $extension ?: 'jpg';
                $filename = "books/google_{$volumeId}.{$extension}";

                Storage::disk('public')->put($filename, $response->body());

                return $filename;
            }
        } catch (\Exception $e) {
            // Log error but don't fail the import
            logger()->warning("Failed to download cover image", [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }

    /**
     * Check if a book with the given ISBN already exists
     *
     * @param string $isbn
     * @return bool
     */
    public function bookExistsByIsbn(string $isbn): bool
    {
        return Book::where('isbn', $isbn)->exists();
    }
}


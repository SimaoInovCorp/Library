<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Http\UploadedFile;

class BookService
{
    /**
     * Create a new book with validated data and handle file upload and author sync.
     */
    public function create(array $validated, ?UploadedFile $coverImage = null): Book
    {
        $book = new Book();
        $book->fill($validated);
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
}

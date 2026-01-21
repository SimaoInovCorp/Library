<?php

namespace App\Services\Authors;

use App\Models\Author;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AuthorService
{
    /**
     * Create a new author with validated data and handle file upload.
     */
    public function create(array $validated, ?UploadedFile $picture = null): Author
    {
        $author = new Author();
        $author->fill($validated);
        if ($picture) {
            $author->picture = $picture->store('authors', 'public');
        }
        $author->save();
        return $author;
    }

    /**
     * Update an existing author with validated data and handle file upload.
     */
    public function update(Author $author, array $validated, ?UploadedFile $picture = null): Author
    {
        $author->fill($validated);
        if ($picture) {
            $author->picture = $picture->store('authors', 'public');
        }
        $author->save();
        return $author;
    }

    /**
     * Delete an author.
     */
    public function delete(Author $author): void
    {
        $author->delete();
    }
}

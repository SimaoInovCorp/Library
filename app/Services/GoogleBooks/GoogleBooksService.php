<?php

namespace App\Services\GoogleBooks;

use App\DataTransferObjects\GoogleBookData;
use App\Services\GoogleBooks\GoogleBooksApiClient;
use App\Services\GoogleBooks\GoogleBooksTransformer;
use App\Services\GoogleBooks\Exceptions\GoogleBooksApiException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

/**
 * Google Books Service
 *
 * Orchestrates API client and transformer to provide high-level operations.
 * Single Responsibility: Business logic for Google Books integration.
 */
class GoogleBooksService
{
    public function __construct(
        private readonly GoogleBooksApiClient $client,
        private readonly GoogleBooksTransformer $transformer,
    ) {}

    /**
     * Search for books and return transformed data
     *
     * @param string $query Search query (title, author, ISBN, etc.)
     * @param int|null $maxResults Maximum number of results
     * @return Collection<GoogleBookData>
     * @throws GoogleBooksApiException
     */
    public function searchBooks(string $query, ?int $maxResults = null): Collection
    {
        try {
            $response = $this->client->search($query, $maxResults);
            return $this->transformer->transformSearchResults($response);
        } catch (GoogleBooksApiException $e) {
            Log::error('Google Books search failed', [
                'query' => $query,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Search for a book by ISBN
     *
     * @param string $isbn ISBN-10 or ISBN-13
     * @return GoogleBookData|null
     * @throws GoogleBooksApiException
     */
    public function searchByIsbn(string $isbn): ?GoogleBookData
    {
        try {
            $response = $this->client->searchByIsbn($isbn);
            $results = $this->transformer->transformSearchResults($response);

            return $results->first();
        } catch (GoogleBooksApiException $e) {
            Log::error('Google Books ISBN search failed', [
                'isbn' => $isbn,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get a specific book by Google Books volume ID
     *
     * @param string $volumeId Google Books volume ID
     * @return GoogleBookData|null
     * @throws GoogleBooksApiException
     */
    public function getBookByVolumeId(string $volumeId): ?GoogleBookData
    {
        try {
            $response = $this->client->getVolumeById($volumeId);
            return $this->transformer->transformVolume($response);
        } catch (GoogleBooksApiException $e) {
            Log::error('Google Books volume retrieval failed', [
                'volume_id' => $volumeId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Search for books by author name
     *
     * @param string $authorName Author's name
     * @param int|null $maxResults Maximum number of results
     * @return Collection<GoogleBookData>
     */
    public function searchByAuthor(string $authorName, ?int $maxResults = null): Collection
    {
        return $this->searchBooks("inauthor:{$authorName}", $maxResults);
    }

    /**
     * Search for books by title
     *
     * @param string $title Book title
     * @param int|null $maxResults Maximum number of results
     * @return Collection<GoogleBookData>
     */
    public function searchByTitle(string $title, ?int $maxResults = null): Collection
    {
        return $this->searchBooks("intitle:{$title}", $maxResults);
    }

    /**
     * Search for books by publisher
     *
     * @param string $publisher Publisher name
     * @param int|null $maxResults Maximum number of results
     * @return Collection<GoogleBookData>
     */
    public function searchByPublisher(string $publisher, ?int $maxResults = null): Collection
    {
        return $this->searchBooks("inpublisher:{$publisher}", $maxResults);
    }
}

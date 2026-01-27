<?php

namespace App\Services\GoogleBooks;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use App\Services\GoogleBooks\Exceptions\GoogleBooksApiException;

/**
 * Google Books API Client
 *
 * Handles all HTTP communication with the Google Books API.
 * Single Responsibility: API communication only.
 */
class GoogleBooksApiClient
{
    private string $baseUrl;
    private string $apiKey;
    private int $maxResults;

    public function __construct()
    {
        $this->baseUrl = config('services.google_books.base_url');
        $this->apiKey = config('services.google_books.api_key');
        $this->maxResults = config('services.google_books.max_results');
    }

    /**
     * Search for books by query string
     *
     * @param string $query Search query (title, author, ISBN, etc.)
     * @param int|null $maxResults Maximum number of results
     * @return array API response data
     * @throws GoogleBooksApiException
     */
    public function search(string $query, ?int $maxResults = null): array
    {
        try {
            $response = Http::timeout(30)
                ->get("{$this->baseUrl}/volumes", [
                    'q' => $query,
                    'key' => $this->apiKey,
                    'maxResults' => $maxResults ?? $this->maxResults,
                    'printType' => 'books',
                ]);

            $this->handleResponse($response);

            return $response->json();
        } catch (\Exception $e) {
            throw new GoogleBooksApiException(
                "Failed to search Google Books: {$e->getMessage()}",
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * Get a specific book by volume ID
     *
     * @param string $volumeId Google Books volume ID
     * @return array API response data
     * @throws GoogleBooksApiException
     */
    public function getVolumeById(string $volumeId): array
    {
        try {
            $response = Http::timeout(30)
                ->get("{$this->baseUrl}/volumes/{$volumeId}", [
                    'key' => $this->apiKey,
                ]);

            $this->handleResponse($response);

            return $response->json();
        } catch (\Exception $e) {
            throw new GoogleBooksApiException(
                "Failed to get book details: {$e->getMessage()}",
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * Search by ISBN
     *
     * @param string $isbn ISBN-10 or ISBN-13
     * @return array API response data
     * @throws GoogleBooksApiException
     */
    public function searchByIsbn(string $isbn): array
    {
        return $this->search("isbn:{$isbn}", 1);
    }

    /**
     * Handle API response and check for errors
     *
     * @param Response $response
     * @throws GoogleBooksApiException
     */
    private function handleResponse(Response $response): void
    {
        if ($response->failed()) {
            $error = $response->json('error.message') ?? 'Unknown API error';
            throw new GoogleBooksApiException(
                "Google Books API error: {$error}",
                $response->status()
            );
        }

        if (!$response->successful()) {
            throw new GoogleBooksApiException(
                'Google Books API returned unsuccessful status',
                $response->status()
            );
        }
    }
}

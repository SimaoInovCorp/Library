<?php

namespace App\Services\GoogleBooks;

use App\DataTransferObjects\GoogleBookData;
use Illuminate\Support\Collection;

/**
 * Google Books Transformer
 *
 * Transforms Google Books API responses into application DTOs.
 * Single Responsibility: Data transformation only.
 */
class GoogleBooksTransformer
{
    /**
     * Transform a collection of GoogleBookData to an array of books with valid ISBNs for the view
     *
     * @param \Illuminate\Support\Collection $books
     * @return array
     */
    public function transformForView(Collection $books): array
    {
        return $books
            ->filter(fn($book) => $book->hasIsbn())
            ->map(fn($book) => $book->toArray())
            ->values()
            ->all();
    }

    /**
     * Transform search results into collection of GoogleBookData DTOs
     *
     * @param array $apiResponse Raw API response from search
     * @return Collection<GoogleBookData>
     */
    public function transformSearchResults(array $apiResponse): Collection
    {
        if (!isset($apiResponse['items']) || empty($apiResponse['items'])) {
            return collect();
        }

        return collect($apiResponse['items'])
            ->map(fn($item) => $this->transformVolume($item))
            ->filter(); // Remove null values
    }

    /**
     * Transform a single volume into GoogleBookData DTO
     *
     * @param array $volume Single volume from API response
     * @return GoogleBookData|null
     */
    public function transformVolume(array $volume): ?GoogleBookData
    {
        $volumeInfo = $volume['volumeInfo'] ?? [];
        $saleInfo = $volume['saleInfo'] ?? [];

        // Skip if no title
        if (empty($volumeInfo['title'])) {
            return null;
        }

        $identifiers = $this->extractIsbn($volumeInfo);

        return new GoogleBookData(
            volumeId: $volume['id'] ?? '',
            isbn13: $identifiers['isbn13'],
            isbn10: $identifiers['isbn10'],
            title: $volumeInfo['title'] ?? '',
            description: $volumeInfo['description'] ?? null,
            authors: $volumeInfo['authors'] ?? [],
            publisher: $volumeInfo['publisher'] ?? null,
            publishedDate: $volumeInfo['publishedDate'] ?? null,
            thumbnailUrl: $this->extractThumbnail($volumeInfo),
            price: $this->extractPrice($saleInfo),
            currencyCode: $saleInfo['listPrice']['currencyCode'] ?? null,
            pageCount: $volumeInfo['pageCount'] ?? null,
            language: $volumeInfo['language'] ?? null,
        );
    }

    /**
     * Extract ISBN-13 and ISBN-10 from industry identifiers
     *
     * @param array $volumeInfo
     * @return array ['isbn13' => ?string, 'isbn10' => ?string]
     */
    private function extractIsbn(array $volumeInfo): array
    {
        $identifiers = $volumeInfo['industryIdentifiers'] ?? [];

        $isbn13 = null;
        $isbn10 = null;

        foreach ($identifiers as $identifier) {
            if ($identifier['type'] === 'ISBN_13') {
                $isbn13 = $identifier['identifier'] ?? null;
            } elseif ($identifier['type'] === 'ISBN_10') {
                $isbn10 = $identifier['identifier'] ?? null;
            }
        }

        return [
            'isbn13' => $isbn13,
            'isbn10' => $isbn10,
        ];
    }

    /**
     * Extract thumbnail URL with fallback to higher resolution if available
     *
     * @param array $volumeInfo
     * @return string|null
     */
    private function extractThumbnail(array $volumeInfo): ?string
    {
        $imageLinks = $volumeInfo['imageLinks'] ?? [];

        // Prefer higher resolution images
        return $imageLinks['large']
            ?? $imageLinks['medium']
            ?? $imageLinks['small']
            ?? $imageLinks['thumbnail']
            ?? $imageLinks['smallThumbnail']
            ?? null;
    }

    /**
     * Extract price from sale info
     *
     * @param array $saleInfo
     * @return float|null
     */
    private function extractPrice(array $saleInfo): ?float
    {
        if (isset($saleInfo['listPrice']['amount'])) {
            return (float) $saleInfo['listPrice']['amount'];
        }

        if (isset($saleInfo['retailPrice']['amount'])) {
            return (float) $saleInfo['retailPrice']['amount'];
        }

        return null;
    }
}

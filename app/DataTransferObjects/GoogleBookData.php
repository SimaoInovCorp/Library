<?php

namespace App\DataTransferObjects;

/**
 * Data Transfer Object for Google Books data
 *
 * Provides type-safe structure for book data from Google Books API
 */
class GoogleBookData
{
    public function __construct(
        public readonly string $volumeId,
        public readonly ?string $isbn13,
        public readonly ?string $isbn10,
        public readonly string $title,
        public readonly ?string $description,
        public readonly array $authors,
        public readonly ?string $publisher,
        public readonly ?string $publishedDate,
        public readonly ?string $thumbnailUrl,
        public readonly ?float $price,
        public readonly ?string $currencyCode,
        public readonly ?int $pageCount,
        public readonly ?string $language,
    ) {}

    /**
     * Get the preferred ISBN (13 over 10)
     */
    public function getPreferredIsbn(): ?string
    {
        return $this->isbn13 ?? $this->isbn10;
    }

    /**
     * Check if book has valid ISBN
     */
    public function hasIsbn(): bool
    {
        return !empty($this->isbn13) || !empty($this->isbn10);
    }

    /**
     * Convert to array format for database insertion
     */
    public function toArray(): array
    {
        return [
            'volume_id' => $this->volumeId,
            'isbn' => $this->getPreferredIsbn(),
            'title' => $this->title,
            'description' => $this->description,
            'authors' => $this->authors,
            'publisher' => $this->publisher,
            'published_date' => $this->publishedDate,
            'thumbnail_url' => $this->thumbnailUrl,
            'price' => $this->price,
            'currency_code' => $this->currencyCode,
            'page_count' => $this->pageCount,
            'language' => $this->language,
        ];
    }
}

<?php

namespace App\Services\Books;

use App\Models\Book;
use App\Services\TextAnalysisService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

class BookRecommendationService
{
    /**
     * @var TextAnalysisService
     */
    private TextAnalysisService $textAnalysisService;

    /**
     * Cache duration in seconds (1 hour)
     */
    private const CACHE_DURATION = 3600;

    /**
     * Constructor with dependency injection.
     *
     * @param TextAnalysisService $textAnalysisService
     */
    public function __construct(TextAnalysisService $textAnalysisService)
    {
        $this->textAnalysisService = $textAnalysisService;
    }

    /**
     * Get related books based on bibliography text similarity.
     *
     * @param Book $book
     * @param int $limit
     * @return Collection
     */
    public function getRelatedBooks(Book $book, int $limit = 5): Collection
    {
        // Use cache to improve performance
        $cacheKey = "book_recommendations_{$book->id}";

        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($book, $limit) {
            return $this->calculateRelatedBooks($book, $limit);
        });
    }

    /**
     * Calculate related books by comparing bibliography similarity.
     *
     * @param Book $book
     * @param int $limit
     * @return Collection
     */
    private function calculateRelatedBooks(Book $book, int $limit): Collection
    {
        // Get keywords from the current book's bibliography
        $currentKeywords = $this->textAnalysisService->extractKeywords($book->bibliography);

        // If no keywords found, return empty collection
        if (empty($currentKeywords)) {
            return collect([]);
        }

        // Get all other books (exclude current book)
        $otherBooks = Book::where('id', '!=', $book->id)
            ->whereNotNull('bibliography')
            ->where('bibliography', '!=', '')
            ->with(['authors', 'publisher'])
            ->get();

        // Calculate similarity scores for each book
        $bookScores = [];

        foreach ($otherBooks as $otherBook) {
            $otherKeywords = $this->textAnalysisService->extractKeywords($otherBook->bibliography);

            if (empty($otherKeywords)) {
                continue;
            }

            $similarity = $this->textAnalysisService->calculateSimilarity($currentKeywords, $otherKeywords);

            // Only include books with some similarity (> 0)
            if ($similarity > 0) {
                $bookScores[] = [
                    'book' => $otherBook,
                    'similarity' => $similarity,
                    'similarity_percentage' => round($similarity * 100, 1),
                ];
            }
        }

        // Sort by similarity (highest first)
        usort($bookScores, function ($a, $b) {
            return $b['similarity'] <=> $a['similarity'];
        });

        // Limit results and return collection
        return collect(array_slice($bookScores, 0, $limit));
    }

    /**
     * Clear cache for a specific book's recommendations.
     *
     * @param Book $book
     * @return void
     */
    public function clearCache(Book $book): void
    {
        $cacheKey = "book_recommendations_{$book->id}";
        Cache::forget($cacheKey);
    }

    /**
     * Clear all book recommendation caches.
     *
     * @return void
     */
    public function clearAllCaches(): void
    {
        // Get all book IDs
        $bookIds = Book::pluck('id');

        foreach ($bookIds as $bookId) {
            Cache::forget("book_recommendations_{$bookId}");
        }
    }
}

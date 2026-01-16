<?php

namespace App\Services\Books;

use App\Models\Book;
use Illuminate\Database\Eloquent\Builder;

class BookQueryService
{
    /**
     * Get filtered, sorted, and searched books query.
     *
     * @param array $params
     * @return Builder
     */
    public function getFilteredBooks(array $params): Builder
    {
        $query = Book::with(['publisher', 'authors']);

        $sort = $params['sort'] ?? 'name';
        $direction = $params['direction'] ?? 'asc';
        $validSorts = ['name'];
        $validDirections = ['asc', 'desc'];
        $sort = in_array($sort, $validSorts) ? $sort : 'name';
        $direction = in_array($direction, $validDirections) ? $direction : 'asc';

        if (!empty($params['search'])) {
            $search = $params['search'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        return $query->orderBy($sort, $direction);
    }
}

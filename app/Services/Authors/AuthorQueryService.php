<?php

namespace App\Services\Authors;

use App\Models\Author;

class AuthorQueryService
{
    /**
     * Get filtered and sorted authors for index page.
     *
     * @param array $params
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getFilteredAuthors(array $params)
    {
        $sort = $params['sort'] ?? 'name';
        $direction = $params['direction'] ?? 'asc';
        $validSorts = ['name'];
        $validDirections = ['asc', 'desc'];
        $sort = in_array($sort, $validSorts) ? $sort : 'name';
        $direction = in_array($direction, $validDirections) ? $direction : 'asc';

        $query = Author::query();

        if (!empty($params['search'])) {
            $query->where('name', 'like', "%{$params['search']}%");
        }

        return $query->orderBy($sort, $direction);
    }
}

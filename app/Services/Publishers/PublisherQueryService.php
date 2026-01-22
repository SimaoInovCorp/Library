<?php

namespace App\Services\Publishers;

use App\Models\Publisher;

class PublisherQueryService
{
    public function getFilteredPublishers(array $params = [])
    {
        $sort = $params['sort'] ?? 'name';
        $direction = $params['direction'] ?? 'asc';
        $search = $params['search'] ?? null;
        $validSorts = ['name'];
        $validDirections = ['asc', 'desc'];
        $sort = in_array($sort, $validSorts) ? $sort : 'name';
        $direction = in_array($direction, $validDirections) ? $direction : 'asc';

        $query = Publisher::query();
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }
        return $query->orderBy($sort, $direction);
    }
}

<?php

namespace App\Services;

use App\Models\Publisher;
use App\Models\Author;

class BookFormService
{
    /**
     * Get publishers and authors for book forms.
     *
     * @return array
     */
    public function getFormData(): array
    {
        return [
            'publishers' => Publisher::orderBy('name')->get(),
            'authors' => Author::orderBy('name')->get(),
        ];
    }
}

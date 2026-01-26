<?php

namespace App\Services\GoogleBooks;

use App\DataTransferObjects\GoogleBookData;
use Illuminate\Http\Request;

/**
 * Factory for creating GoogleBookData DTOs from various sources
 */
class GoogleBookDataFactory
{
    /**
     * Create a GoogleBookData DTO from a request (e.g., import form)
     */
    public static function fromRequest(Request $request): GoogleBookData
    {
        return new GoogleBookData(
            volumeId: $request->input('volume_id'),
            isbn13: $request->input('isbn'),
            isbn10: null, // Not available from form
            title: $request->input('title'),
            description: $request->input('description'),
            authors: $request->input('authors', []),
            publisher: $request->input('publisher'),
            publishedDate: $request->input('published_date'),
            thumbnailUrl: $request->input('thumbnail_url'),
            price: $request->input('price'),
            currencyCode: $request->input('currency_code'),
            pageCount: $request->input('page_count'),
            language: $request->input('language'),
        );
    }
}

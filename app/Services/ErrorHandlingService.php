<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Exception;

class ErrorHandlingService
{
    /**
     * Handle exception, log error, and redirect with user-friendly message.
     */
    public function handle(Exception $e, string $context, string $redirectRoute = 'books.index', string $userMessage = 'An error occurred. Please try again.')
    {
        Log::error($context . ': ' . $e->getMessage());
        return redirect()->route($redirectRoute)->with('error', $userMessage);
    }
}

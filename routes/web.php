<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    $featuredReviews = app(\App\Services\Books\ReviewService::class)->getFeaturedReviews(5);
    return view('home', compact('featuredReviews'));
})->name('home');

Route::get('/about', function () {
    return view('profile.about');
})->name('profile.about');

Route::get('/contact', function () {
    return view('profile.contact');
})->name('profile.contact');


// Book CRUD routes (protected by auth & verified middleware)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::resource('books', \App\Http\Controllers\BookController::class);
    Route::get('books/export/csv', [\App\Http\Controllers\BookController::class, 'exportCsv'])->name('books.export.csv');

    // Google Books Import
    Route::get('books-import/google', [\App\Http\Controllers\GoogleBooksImportController::class, 'index'])->name('books.import.google');
    Route::get('books-import/google/search', [\App\Http\Controllers\GoogleBooksImportController::class, 'search'])->name('books.import.google.search');
    Route::post('books-import/google/import', [\App\Http\Controllers\GoogleBooksImportController::class, 'import'])->name('books.import.google.import');

    // Book requisition route (for citizens)
    Route::post('books/{book}/requisitions', [\App\Http\Controllers\RequisitionController::class, 'store'])->name('books.requisitions.store');
});


// Publisher CRUD routes (protected by auth & verified middleware)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::resource('publishers', \App\Http\Controllers\PublisherController::class);
});


// Author CRUD routes (protected by auth & verified middleware)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::resource('authors', \App\Http\Controllers\AuthorController::class);
});

// Dashboard route (protected by auth & verified middleware)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Requisitions page for citizens
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('requisitions', [\App\Http\Controllers\RequisitionController::class, 'index'])->name('requisitions.index');
    Route::post('requisitions/{requisition}/return', [\App\Http\Controllers\RequisitionController::class, 'return'])->name('requisitions.return');
    Route::post('requisitions/{requisition}/approve', [\App\Http\Controllers\RequisitionController::class, 'approve'])->middleware('admin')->name('requisitions.approve');
    Route::get('requisitions/{requisition}', [\App\Http\Controllers\RequisitionController::class, 'show'])->name('requisitions.show');
    Route::post('books/{book}/requisitions/{requisition}/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    Route::get('reviews/{review}', [\App\Http\Controllers\ReviewController::class, 'show'])->name('reviews.show');
    Route::delete('reviews/{review}', [\App\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy');
});

// Admin routes (protected by auth & admin middleware)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // User management routes
    Route::resource('users', \App\Http\Controllers\UserController::class);

    // Review moderation routes
    Route::get('admin/reviews', [\App\Http\Controllers\ReviewController::class, 'adminIndex'])->name('admin.reviews.index');
    Route::post('admin/reviews/{review}/approve', [\App\Http\Controllers\ReviewController::class, 'approve'])->name('admin.reviews.approve');
    Route::post('admin/reviews/{review}/reject', [\App\Http\Controllers\ReviewController::class, 'reject'])->name('admin.reviews.reject');
});

// Notifications routes
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('notifications', function () {
        return view('notifications.index');
    })->name('notifications.index');
    Route::post('notifications/{notification}/read', function ($notificationId) {
        $notification = Auth::user()->notifications()->findOrFail($notificationId); //False positive error from intelephense
        $notification->markAsRead();
        return back();
    })->name('notifications.markAsRead');
});

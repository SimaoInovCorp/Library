<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
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
});

// Admin routes (protected by auth & admin middleware)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

});

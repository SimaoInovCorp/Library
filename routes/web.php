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

Route::get('/books', function () {
    return view('library.books');
})->name('library.books');


// Publisher CRUD routes (protected by auth & verified middleware)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::resource('publishers', \App\Http\Controllers\PublisherController::class);
});

Route::get('/authors', function () {
    return view('library.authors');
})->name('library.authors');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

});

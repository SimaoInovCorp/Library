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

// Contact form submission route
Route::post('/contact', [\App\Http\Controllers\ContactController::class, 'submit'])->name('contact.submit');

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

    // Admin Order Management
    Route::get('admin/orders', [\App\Http\Controllers\Admin\AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('admin/orders/{order}', [\App\Http\Controllers\Admin\AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::post('admin/orders/{order}/cancel', [\App\Http\Controllers\Admin\AdminOrderController::class, 'cancel'])->name('admin.orders.cancel');
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

// Shopping Cart routes (for regular users only, not admins)
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('cart', [\App\Http\Controllers\CartController::class, 'store'])->name('cart.store');
    Route::put('cart/{cartItem}', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::delete('cart/{cartItem}', [\App\Http\Controllers\CartController::class, 'destroy'])->name('cart.destroy');
    Route::post('cart/clear', [\App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear');
});

// Checkout routes (for regular users only, not admins)
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('checkout/step1', [\App\Http\Controllers\CheckoutController::class, 'step1'])->name('checkout.step1');
    Route::get('checkout/step2', [\App\Http\Controllers\CheckoutController::class, 'step2'])->name('checkout.step2');
    Route::post('checkout/address', [\App\Http\Controllers\CheckoutController::class, 'saveAddress'])->name('checkout.saveAddress');
    Route::get('checkout/payment/{order}', [\App\Http\Controllers\CheckoutController::class, 'step3'])->name('checkout.step3');
    Route::post('checkout/payment/{order}/process', [\App\Http\Controllers\CheckoutController::class, 'processPayment'])->name('checkout.processPayment');
});

// Order routes (for regular users to view their orders)
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [\App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
});

// Stripe Webhook (no CSRF protection, no auth)
Route::post('stripe/webhook', [\App\Http\Controllers\StripeWebhookController::class, 'handle'])->name('stripe.webhook');

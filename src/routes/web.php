<?php

use Illuminate\Support\Facades\Route;

/*
|----------------------------------------------------------------------
| Web Routes
|----------------------------------------------------------------------
|
| Register web routes for your application here. These routes are loaded
| by the RouteServiceProvider and assigned to the "web" middleware group.
| Make something great!
|
| Best Practices for Named Routes:
| - Use descriptive, concise names for routes.
| - Avoid hardcoded URLs; always use named routes.
| - Ensure consistency by referring to routes by name.
|
| Route Naming Conventions:
| - For CRUD operations, use resource controllers:
|     - Example: `Route::resource('posts', 'PostController');`
|     - Named routes: `posts.index`, `posts.show`, `posts.create`, etc.
| - For detailed routes, use clear and concise names:
|     - Example (Dot Notation): `Route::get('/user/{id}/profile', 'ProfileController@show')->name('user.profile.show');`
|     - Example (Hyphen Notation): `Route::post('/user/{id}/profile/update', 'ProfileController@update')->name('user-profile.update');`
|
| Usage:
| - Redirect: `return redirect()->route('post-attribute-data.index');`
| - Generate URL: `$url = route('post-attribute-data.store');`
| - Blade (GET): `<a href="{{ route('post-attribute-data.index') }}">Link</a>`
| - Blade (POST): `<form action="{{ route('post-attribute-data.store') }}" method="POST">`
| - Blade (PUT): `<form action="{{ route('post-attribute-data.update') }}" method="POST"> @method('PUT') </form>`
| - Blade (DELETE): `<form action="{{ route('post-attribute-data.destroy') }}" method="POST"> @method('DELETE') </form>`
*/

use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PushSubscriptionController;

Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/password/reset', function () {
    return view('auth.passwords.email');
})->name('password.request');

Route::post('/password/email', [AuthController::class, 'sendResetLink'])->name('password.email');

Route::get('/password/reset/{token}', function ($token) {
    return view('auth.passwords.reset', ['token' => $token]);
})->name('password.reset');

Route::post('/password/reset', [AuthController::class, 'resetPassword'])->name('password.update');

Route::middleware(['auth'])->group(function () {
    //
    Route::get('/', function () {
        return view('welcome');
    });

    Route::prefix('push-subscriptions')->name('push-subscriptions.')->group(function () {
        Route::post('subscribe', [PushSubscriptionController::class, 'store'])->name('store');
        Route::post('unsubscribe', [PushSubscriptionController::class, 'destroy'])->name('destroy');
    });

    // User CRUD operations
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('create', [UserController::class, 'create'])->name('create');
        Route::post('create', [UserController::class, 'store'])->name('store');
        Route::get('{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::post('{user}/edit', [UserController::class, 'update'])->name('update');
        Route::post('{user}/delete', [UserController::class, 'destroy'])->name('destroy');
        Route::get('{user}/show', [UserController::class, 'show'])->name('show');
    });

    // Company CRUD operations
    Route::prefix('companies')->name('companies.')->group(function () {
        Route::get('/', [CompanyController::class, 'index'])->name('index');
        Route::get('create', [CompanyController::class, 'create'])->name('create');
        Route::post('create', [CompanyController::class, 'store'])->name('store');
        Route::get('{company}/edit', [CompanyController::class, 'edit'])->name('edit');
        Route::post('{company}/edit', [CompanyController::class, 'update'])->name('update');
        Route::post('{company}/delete', [CompanyController::class, 'destroy'])->name('destroy');
        Route::get('{company}/show', [CompanyController::class, 'show'])->name('show');
    });

    // Product CRUD operations
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('create', [ProductController::class, 'create'])->name('create');
        Route::post('create', [ProductController::class, 'store'])->name('store');
        Route::get('{product}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::post('{product}/edit', [ProductController::class, 'update'])->name('update');
        Route::post('{product}/delete', [ProductController::class, 'destroy'])->name('destroy');
        Route::get('{product}/show', [ProductController::class, 'show'])->name('show');
    });

    // Stock CRUD operations
    Route::prefix('stocks')->name('stocks.')->group(function () {
        Route::get('/', [StockController::class, 'index'])->name('index');
        Route::get('create', [StockController::class, 'create'])->name('create');
        Route::post('create', [StockController::class, 'store'])->name('store');
        Route::get('{stock}/edit', [StockController::class, 'edit'])->name('edit');
        Route::post('{stock}/edit', [StockController::class, 'update'])->name('update');
        Route::post('{stock}/delete', [StockController::class, 'destroy'])->name('destroy');
        Route::get('{stock}/show', [StockController::class, 'show'])->name('show');
    });

    // StockMovement CRUD operations
    Route::prefix('stock-movements')->name('stock-movements.')->group(function () {
        Route::post('{stock}/create', [StockMovementController::class, 'store'])->name('store');
        Route::post('{stockMovement}/edit', [StockMovementController::class, 'update'])->name('update');
        Route::post('{stockMovement}/delete', [StockMovementController::class, 'destroy'])->name('destroy');
        Route::get('{stockMovement}/show', [StockMovementController::class, 'show'])->name('show');
        Route::get('{stock}/{stockMovement?}/', [StockMovementController::class, 'index'])->name('index');
    });

    // Cart CRUD operations
    Route::prefix('carts')->name('carts.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::get('create', [CartController::class, 'create'])->name('create');
        Route::post('create', [CartController::class, 'store'])->name('store');
        Route::get('{cart}/edit', [CartController::class, 'edit'])->name('edit');
        Route::post('{cart}/edit', [CartController::class, 'update'])->name('update');
        Route::post('{cart}/delete', [CartController::class, 'destroy'])->name('destroy');
        Route::get('{cart}/show', [CartController::class, 'show'])->name('show');
        Route::get('checkout', [CartController::class, 'checkout'])->name('checkout');
    });

    // Order CRUD operations
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::post('create', [OrderController::class, 'store'])->name('store');
        Route::post('{order}/edit', [OrderController::class, 'update'])->name('update');
        Route::post('{order}/delete', [OrderController::class, 'destroy'])->name('destroy');
        Route::get('{order}/show', [OrderController::class, 'show'])->name('show');
        Route::get('company', [OrderController::class, 'companyIndex'])->name('company-index');
    });
    //
});

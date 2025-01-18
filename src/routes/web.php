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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([])->group(function () {
    //
    Route::get('/', function () {
        return view('welcome');
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
    //
});

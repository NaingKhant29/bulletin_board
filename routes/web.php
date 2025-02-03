<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\RegisterController;

Route::get('/', [PostController::class, 'index']);
Route::get('/users', [UserController::class, 'dexin'])->name('users.dexin');
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/detail/{id}', [
PostController::class,
'detail'
]);
Route::get('/posts/more', function() {
    return redirect('/posts/detail');
});

Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
Route::get('/posts/confirm', [PostController::class, 'confirm'])->name('posts.confirm');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

// Route for showing the edit form
Route::get('/posts/edit/{id}', [PostController::class, 'edit'])->name('posts.edit');

// Route for confirming the edit (this just shows the confirm page)
Route::post('/posts/confirmedit/{id}', [PostController::class, 'confirmedit'])->name('posts.confirmedit');

Route::put('/posts/update/{id}', [PostController::class, 'update'])->name('posts.update');
Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
// Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
Route::get('/posts/upload', function () {
    return view('posts.upload');
})->name('posts.upload');
Route::post('/posts/upload', [PostController::class, 'upload'])->name('posts.upload');
Route::get('posts/download', [PostController::class, 'download'])->name('posts.download');

Route::get('register/show', [RegisterController::class, 'showRegistrationForm'])->name('register.show');
Route::post('register/create', [RegisterController::class, 'create'])->name('register.create');


Auth::routes();


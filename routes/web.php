<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

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



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

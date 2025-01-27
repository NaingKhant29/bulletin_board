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
Route::get('/posts/delete/{id}', [
PostController::class,
'delete'
]);
   

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PostController;


Route::get('/posts', function () {
    return 'Posts List';
   });
   Route::get('/posts/detail', function () {
    return 'Post Detail';
   });
   
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/detail/{id}', [
PostController::class,
'detail'
]);


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

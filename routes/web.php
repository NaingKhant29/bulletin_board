<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CategoryController;

// # Authenticated User Routes
Route::middleware(['auth'])->group(function () {

    // # Post Routes
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::get('/posts/edit/{id}', [PostController::class, 'edit'])->name('posts.edit');
    Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/upload', [PostController::class, 'upload'])->name('posts.upload');
    Route::get('/posts/detail/{id}', [PostController::class, 'detail']);
    Route::get('/posts/more', function () {
        return redirect('/posts/detail');
    });
    // Route for creating a category
    Route::post('category/store', [CategoryController::class, 'store'])->name('categories.store');

    // Route for deleting a category
    Route::delete('category/delete', [CategoryController::class, 'delete'])->name('categories.delete');


    // # User Routes
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/profile/edit', [UserController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update/{id}', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::get('/change-password', [UserController::class, 'showChangePasswordForm'])->name('users.pwchange');
    Route::post('/update-password', [UserController::class, 'changePassword'])->name('users.pwupdate');

    // # Reaction Routes
    Route::post('/reactions', [ReactionController::class, 'store'])->name('reactions.store');
    Route::get('/reactions/{post_id}', [ReactionController::class, 'index'])->name('reactions.index');

    // # Comment Routes
    Route::post('/posts/{post}/comment', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // # Homepage Redirect
    Route::get('/', [PostController::class, 'index']);
});

// # Admin-only Routes
Route::middleware(['admin'])->group(function () {

    // # Admin - Post Management
    Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');

    // # Admin - User Management
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/show', [UserController::class, 'showRegistrationForm'])->name('users.show');
    Route::post('/users/confirm', [UserController::class, 'confirm'])->name('users.confirm');
    Route::post('/users', [UserController::class, 'new'])->name('users.new');
});

// # Post Routes (General)
Route::post('/posts/confirm', [PostController::class, 'confirm'])->name('posts.confirm');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
Route::get('/posts/confirmedit/{id}', [PostController::class, 'confirmedit'])->name('posts.confirmedit');
Route::put('/posts/update/{id}', [PostController::class, 'update'])->name('posts.update');
Route::get('/posts/upload', function () {
    return view('posts.upload');
})->name('posts.upload');
Route::get('posts/download', [PostController::class, 'download'])->name('posts.download');
Route::get('/posts/{id}/download', [PostController::class, 'downloadSingle'])->name('posts.downloadSingle');

// # Password Reset Routes (Guest Only)
Route::middleware(['guest'])->group(function () {
    Route::get('/forgot-password', function () {
        return view('auth.passwords.email');
    })->name('password.request');

    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendPasswordResetLink'])
        ->name('password.email');

    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::patch('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// # Authentication Routes
Auth::routes(['reset' => false]);

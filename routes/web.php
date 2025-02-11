<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Str;

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



// Handle user registration


Route::get('/posts/upload', function () {
    return view('posts.upload');
})->name('posts.upload');
Route::post('/posts/upload', [PostController::class, 'upload'])->name('posts.upload');
Route::get('posts/download', [PostController::class, 'download'])->name('posts.download');
Route::get('/profile/edit', [UserController::class, 'edit'])->name('profile.edit');
Route::put('/profile/update/{id}', [UserController::class, 'updateProfile'])->name('profile.update');

Route::get('/users/show', [UserController::class, 'showRegistrationForm'])->name('users.show');
Route::post('/users/confirm', [UserController::class, 'confirm'])->name('users.confirm');

Route::post('/users', [UserController::class, 'new'])->name('users.new');

// Show Change Password Form
Route::get('/change-password', [UserController::class, 'showChangePasswordForm'])->name('users.pwchange');
Route::post('/update-password', [UserController::class, 'changePassword'])->name('users.pwupdate');

Route::get('/forgot-password', function () {
    return view('auth.passwords.email'); // Adjusted to return the 'email' view
})->middleware('guest')->name('password.request');


Route::post('/forgot-password', [ForgotPasswordController::class, 'sendPasswordResetLink'])
    ->middleware('guest')
    ->name('password.email');

// Password Reset Form Route
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset.form');

// Password Reset Submit Route
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.reset');


    Auth::routes(['reset' => false]);



<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // TODO routes for regular users to submit comments on posts
});

Route::middleware(['auth', 'role:editor,admin'])->prefix('editor')->group(function () {
    Route::get('/dashboard', function () {
        return 'Editor Dashboard'; // TODO editor dashboard
    })->name('editor.dashboard');

    Route::resource('posts', PostController::class);
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return 'Admin Dashboard'; // TODO admin dashboard
    })->name('admin.dashboard');

    // TODO routes for viewing all users and upgrading their roles
});


require __DIR__.'/auth.php';

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BadgeController;
use App\Http\Controllers\UserBadgeController;
use App\Http\Controllers\BlogController;

/*
|--------------------------------------------------------------------------
| Web Routes - Configuration corrigée
|--------------------------------------------------------------------------
*/

// Pages publiques
Route::get('/', function () {
    return view('login');
})->name('login');

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

/* -----------------------------
|        Badge Routes
------------------------------*/
Route::get('/badge', [BadgeController::class, 'index'])->name('badge.index');
Route::post('/badge/create', [BadgeController::class, 'create'])->name('badge.create');
Route::put('/badge/update', [BadgeController::class, 'update'])->name('badge.update');
Route::delete('/badge/delete', [BadgeController::class, 'delete'])->name('badge.delete');
Route::get('/user_badge', [UserBadgeController::class, 'index'])->name('user_badge');

/* -----------------------------
|        Blog Routes (CORRIGÉ)
------------------------------*/

// Routes spécifiques AVANT les routes dynamiques {blog}
Route::get('/blogs/gallery', [BlogController::class, 'gallery'])->name('blogs.gallery');
Route::get('/blogs/create', [BlogController::class, 'create'])->name('blogs.create');

// Routes publiques
Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index');
Route::get('/blogs/{blog}', [BlogController::class, 'show'])->name('blogs.show');

// Routes protégées par authentification
Route::middleware(['auth'])->group(function () {
    Route::post('/blogs', [BlogController::class, 'store'])->name('blogs.store');
    Route::get('/blogs/{blog}/edit', [BlogController::class, 'edit'])->name('blogs.edit');
    Route::put('/blogs/{blog}', [BlogController::class, 'update'])->name('blogs.update');
    Route::delete('/blogs/{blog}', [BlogController::class, 'destroy'])->name('blogs.destroy');
});
<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BadgeController;
use App\Http\Controllers\UserBadgeController;
Route::get('/', function () {
    return view('login');
})->name('login');
Route::get('/register', function () {
    return view('register');
})->name('register');
Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');
Route::get('/badge',[BadgeController::class, 'index'])->name('badge.index');
Route::post('/badge/create',[BadgeController::class, 'create'])->name('badge.create');
Route::put('/badge/update',[BadgeController::class, 'update'])->name('badge.update');
Route::delete('/badge/delete',[BadgeController::class, 'delete'])->name('badge.delete');
Route::get('/user_badge', [UserBadgeController::class, 'index'])->name('user_badge');

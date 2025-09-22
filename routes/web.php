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
Route::resource('/badge', BadgeController::class);
Route::resource('/user_badge', UserBadgeController::class);

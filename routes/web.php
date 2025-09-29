<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BadgeController;
use App\Http\Controllers\UserBadgeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\EventTypeController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Auth;
Auth::routes();
Route::get('/', function () {
    return view('auth.login');
})->name('login');
Route::get('/register', function () {
    return view('auth.register');
})->name('register');


Route::resource('/badge', BadgeController::class);
Route::resource('/user_badge', UserBadgeController::class);
Route::resource('/events', EventController::class);

Route::resource('/event-types', EventTypeController::class);
Route::resource('/tags', TagController::class);



Route::middleware('auth')->group(function () {
    Route::resource('/registrations', RegistrationController::class);
});

Auth::routes();
Route::post('/welcome', function () {
        return view('welcome');
})->name('welcome');

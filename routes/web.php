<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BadgeController;
use App\Http\Controllers\UserBadgeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\EventTypeController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\OrganisationController;
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
Route::resource('/events', EventController::class);
Route::resource('/registrations', RegistrationController::class);
Route::resource('/event-types', EventTypeController::class);
Route::resource('/tags', TagController::class);
Route::resource('/organisations', OrganisationController::class);

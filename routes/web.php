<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\EventTypeController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/events/frontend', [EventController::class, 'frontevent'])->name('events.frontevent');
Route::resource('events', EventController::class);
Route::resource('event-types', EventTypeController::class);
Route::resource('tags', TagController::class);


Route::get('/welcome', function () {
        return view('welcome');
})->name('welcome');
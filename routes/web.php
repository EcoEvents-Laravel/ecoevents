<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BadgeController;
use App\Http\Controllers\UserBadgeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\EventTypeController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\OrganisationController;
use App\Http\Controllers\BlogController;
Auth::routes();
Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/login', function () {
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
Route::resource('/organisations', App\Http\Controllers\OrganisationController::class);

// Routes spécifiques AVANT les routes dynamiques {blog}
Route::get('/blogs/gallery', [BlogController::class, 'gallery'])->name('blogs.gallery');
Route::get('/blogs/create', [BlogController::class, 'create'])->name('blogs.create');

// Routes publiques
Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index');
Route::get('/blogs/{blog}', [BlogController::class, 'show'])->name('blogs.show');



Route::middleware('auth')->group(function () {
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::resource('/registrations', RegistrationController::class);
    Route::get('/chatbot',function () {
        return view('Chatbot-AI');
    })->name('chatbot');
    Route::post('/chatbot', [ChatbotController::class, 'getResponse'])->name('chatbot.response');
    Route::post('/blogs', [BlogController::class, 'store'])->name('blogs.store');
    Route::get('/blogs/{blog}/edit', [BlogController::class, 'edit'])->name('blogs.edit');
    Route::put('/blogs/{blog}', [BlogController::class, 'update'])->name('blogs.update');
    Route::delete('/blogs/{blog}', [BlogController::class, 'destroy'])->name('blogs.destroy');
    
});

Auth::routes();
Route::get('/welcome', function () {
        return view('welcome');
})->name('welcome');

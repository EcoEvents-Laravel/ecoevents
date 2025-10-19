<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventTypeController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\HomeController; // Ajouté pour la route /home
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- Routes d'authentification ---
// Inclus UNE SEULE FOIS pour créer les routes /login, /register, etc.
Auth::routes();

// --- Routes Publiques (accessibles à tous) ---

// Route pour la page d'accueil, nommée 'welcome' pour le nouveau layout.
Route::get('/', [EventController::class, 'index'])->name('welcome');

// Route '/home' qui redirige simplement vers la page d'accueil.
// Utile car Laravel redirige ici par défaut après la connexion.
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Route pour lister tous les événements (déjà créée par `resource` ci-dessous)
// Le lien "Événements" dans ton nouveau layout pointera ici.

// Routes pour la gestion des ressources (événements, types, tags)
Route::resource('events', EventController::class);
Route::resource('event-types', EventTypeController::class);
Route::resource('tags', TagController::class);

// --- Routes Protégées (nécessitent que l'utilisateur soit connecté) ---
Route::middleware('auth')->group(function () {
    
    // Tes routes pour les commentaires
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Tes routes pour les inscriptions
    Route::get('/my-registrations', [RegistrationController::class, 'index'])->name('registrations.index');
    Route::post('/registrations', [RegistrationController::class, 'store'])->name('registrations.store');
    Route::delete('/registrations/{registration}', [RegistrationController::class, 'destroy'])->name('registrations.destroy');
});
<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany; // <-- Ajouté pour les relations
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    // ======================================================================
    // RELATIONS ÉLOQUENT AJOUTÉES
    // ======================================================================

    /**
     * Récupère toutes les inscriptions (registrations) de l'utilisateur.
     * Un utilisateur PEUT AVOIR PLUSIEURS inscriptions.
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Récupère tous les commentaires (comments) de l'utilisateur.
     * Un utilisateur PEUT AVOIR PLUSIEURS commentaires.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
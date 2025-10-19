<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse (Mass Assignment).
     * C'est une protection pour n'autoriser que ces champs à être remplis via un formulaire.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'banner_url',
        'start_date',
        'end_date',
        'address',
        'city',
        'country',
        'max_participants',
        'event_type_id',
        'slug',
        'status',
    ];

    /**
     * Les attributs qui doivent être convertis (castés) vers des types natifs.
     * C'est la solution à ton bug : on dit à Laravel que 'start_date' et 'end_date' sont des objets Date.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    // ======================================================================
    // RELATIONS ÉLOQUENT
    // ======================================================================

    public function eventType(): BelongsTo
    {
        return $this->belongsTo(EventType::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Récupère les commentaires de l'événement.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Récupère les inscriptions à l'événement.
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }
}
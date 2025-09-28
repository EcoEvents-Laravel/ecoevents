<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'content',
        'user_id',
        'event_id',
    ];

    /**
     * Récupère l'utilisateur qui a posté le commentaire.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Récupère l'événement auquel le commentaire est associé.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
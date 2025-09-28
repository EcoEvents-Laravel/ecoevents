<?php

namespace App\Models;

use App\Models\Comment; // <- Ligne ajoutée
use App\Models\Registration; // <- Ligne ajoutée
use App\Models\Tag;
use App\Models\EventType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany; // <- Ligne ajoutée

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'location',
        'start_date',
        'end_date',
        'address',
        'city',
        'postal_code',
        'country',
        'latitude',
        'longitude',
        'max_participants',
        'status',
        'slug',
        'banner_url',
        'event_type_id',
    ];

    public function eventType(): BelongsTo
    {
        return $this->belongsTo(EventType::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Get the comments for the event.
     */
    public function comments(): HasMany
    {
        // Un événement A PLUSIEURS commentaires
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the registrations for the event.
     */
    public function registrations(): HasMany
    {
        // Un événement A PLUSIEURS inscriptions
        return $this->hasMany(Registration::class);
    }
}
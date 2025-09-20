<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'banner_url', 'start_date', 'end_date', 'address',
        'city', 'postal_code', 'country', 'latitude', 'longitude', 'max_participants',
        'status', 'slug', 'event_type_id'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
    ];


    protected static function boot()
    {
        parent::boot();
        static::creating(function ($event) {
            $event->slug = Str::slug($event->title);
        });
    }

    public function eventType()
    {
        return $this->belongsTo(EventType::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}

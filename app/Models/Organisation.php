<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Organisation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'logo_url',
        'website',
        'contact_email',
    ];

    /**
     * Get the organisation's events
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Get the organisation's logo URL or default
     */
    public function getLogoUrlAttribute($value)
    {
        return $value ?: '/images/default-logo.png';
    }
}

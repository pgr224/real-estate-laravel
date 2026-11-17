<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BrokerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'rera_id', 'agency_name', 'specialization',
        'areas_served', 'experience_years', 'total_deals', 'rating',
        'verified', 'bio', 'phone', 'whatsapp', 'social_links', 'team_id',
    ];

    protected $casts = [
        'areas_served' => 'array',
        'social_links' => 'array',
        'verified' => 'boolean',
        'rating' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function scopeVerified($query)
    {
        return $query->where('verified', true);
    }

    public function scopeInArea($query, string $area)
    {
        return $query->whereJsonContains('areas_served', $area);
    }

    public function scopeTopRated($query, float $minRating = 4.0)
    {
        return $query->where('rating', '>=', $minRating)->orderByDesc('rating');
    }
}

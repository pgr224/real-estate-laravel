<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BuilderProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'builder_name', 'rera_number', 'location', 'city',
        'total_units', 'available_units', 'price_range_min', 'price_range_max',
        'possession_date', 'status', 'amenities', 'floor_plans',
        'latitude', 'longitude', 'description', 'logo_url', 'banner_url', 'team_id',
    ];

    protected $casts = [
        'amenities' => 'array',
        'floor_plans' => 'array',
        'possession_date' => 'date',
        'price_range_min' => 'decimal:2',
        'price_range_max' => 'decimal:2',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class, 'builder_project_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInCity($query, string $city)
    {
        return $query->where('city', $city);
    }

    public function getAvailabilityPercentageAttribute(): float
    {
        if ($this->total_units === 0) return 0;
        return round(($this->available_units / $this->total_units) * 100, 1);
    }
}

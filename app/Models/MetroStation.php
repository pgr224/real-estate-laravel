<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class MetroStation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'line_name', 'line_color',
        'latitude', 'longitude', 'city', 'is_operational',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'is_operational' => 'boolean',
    ];

    public function scopeOperational($query)
    {
        return $query->where('is_operational', true);
    }

    public function scopeInCity($query, string $city)
    {
        return $query->where('city', $city);
    }

    public function scopeOnLine($query, string $lineName)
    {
        return $query->where('line_name', $lineName);
    }

    public function scopeNearby(Builder $query, float $latitude, float $longitude, float $radiusKm = 5): Builder
    {
        $haversine = "( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) )";

        return $query->selectRaw("*, {$haversine} AS distance", [$latitude, $longitude, $latitude])
            ->whereRaw("{$haversine} <= ?", [$latitude, $longitude, $latitude, $radiusKm])
            ->orderByRaw("{$haversine} ASC", [$latitude, $longitude, $latitude]);
    }
}

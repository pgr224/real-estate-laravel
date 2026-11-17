<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyMedia extends Model
{
    use HasFactory;

    protected $table = 'property_media';

    protected $fillable = [
        'property_id', 'type', 'url', 'thumbnail_url',
        'duration', 'views_count', 'likes_count', 'caption', 'sort_order',
    ];

    protected $casts = [
        'views_count' => 'integer',
        'likes_count' => 'integer',
        'duration' => 'integer',
        'sort_order' => 'integer',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeReels($query)
    {
        return $query->where('type', 'reel');
    }

    public function scopeVirtualTours($query)
    {
        return $query->where('type', 'virtual_tour');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    public function incrementLikes(): void
    {
        $this->increment('likes_count');
    }
}

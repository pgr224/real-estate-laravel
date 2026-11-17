<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OwnerListing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'property_id', 'listing_type', 'asking_price',
        'is_zero_brokerage', 'contact_phone', 'contact_whatsapp',
        'verification_status', 'documents',
    ];

    protected $casts = [
        'documents' => 'array',
        'is_zero_brokerage' => 'boolean',
        'asking_price' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function scopeForSale($query)
    {
        return $query->where('listing_type', 'sell');
    }

    public function scopeForRent($query)
    {
        return $query->where('listing_type', 'rent');
    }

    public function scopeZeroBrokerage($query)
    {
        return $query->where('is_zero_brokerage', true);
    }

    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }

    public function scopePending($query)
    {
        return $query->where('verification_status', 'pending');
    }
}

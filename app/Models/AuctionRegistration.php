<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuctionRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'auction_id', 'user_id', 'emd_amount',
        'emd_status', 'registration_number',
    ];

    protected $casts = [
        'emd_amount' => 'decimal:2',
    ];

    public function auction(): BelongsTo
    {
        return $this->belongsTo(Auction::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopePaid($query)
    {
        return $query->where('emd_status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('emd_status', 'pending');
    }

    public function isConfirmed(): bool
    {
        return $this->emd_status === 'paid';
    }
}

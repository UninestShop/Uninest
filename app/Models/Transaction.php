<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'product_id',
        'buyer_id',
        'seller_id',
        'amount',
        'status',
        'cancelled_by',
        'cancel_reason',
        'completed_at'
    ];

    protected $casts = [
        'completed_at' => 'datetime'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Get the user that owns the transaction.
     * This will return the buyer by default.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
}

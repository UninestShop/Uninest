<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $fillable = [
        'product_id',
        'buyer_id',
        'seller_id',
        'meeting_time',
        'location',
        'status', // scheduled, completed, cancelled
        'google_meet_link',
        'notes'
    ];

    protected $casts = [
        'meeting_time' => 'datetime',
        'location' => 'array'
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
}

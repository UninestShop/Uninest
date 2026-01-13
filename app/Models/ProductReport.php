<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'reason',
        'details',
        'status',
        'is_report',
    ];

    /**
     * Get the product associated with the report.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who reported the product.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'user_id',
        'status', // pending, approved, rejected, flagged
        'mrp',
        'selling_price',
        'condition', // Make sure this is included
        'category_id',
        'photos',
        'meeting_location',
        'location',
        'lat',
        'long',
        'payment_method',
        'university_id',
    ];

    protected $casts = [
        'meeting_location' => 'array',
        'photos' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($product) {
            if (!isset($product->price)) {
                $product->price = $product->selling_price;
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('selling_price')) {
                $product->price = $product->selling_price;
            }
        });
    }

    // Scopes
    public function scopeByUniversity($query, $universityName)
    {
        return $query->whereHas('user', function($q) use ($universityName) {
            $q->where('university_name', $universityName);
        });
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'approved')
                    ->where('is_sold', false);
    }

    public function scopeReported($query)
    {
        return $query->where('status', 'flagged');
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reports()
    {
        return $this->hasMany(ProductReport::class);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }

    /**
     * Get the university that this product belongs to
     */
    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->mrp > 0) {
            return round((($this->mrp - $this->selling_price) / $this->mrp) * 100, 2);
        }
        return 0;
    }

    public function getFirstPhotoAttribute()
    {
        if (!$this->photos) {
            return null;
        }
        
        $photos = is_string($this->photos) ? json_decode($this->photos) : $this->photos;
        return $photos[0] ?? null;
    }

    public function getPhotosAttribute($value)
    {
        return is_string($value) ? json_decode($value) : $value;
    }
}

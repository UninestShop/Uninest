<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name', // Make sure this is included
        'email',
        'university_email',
        'university_name',
        'password',
        'user_type',
        'slug',
        'profile_picture',
        'current_location',
        'university_location',
        'bod',
        'gender',
        'university_id',
        'country_code',
        'country_iso',
        'remember_token',
        'is_email_verified',
        'latitude',
        'longitude',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'otp_expires_at' => 'datetime',
            'university_location' => 'array',
            'is_email_verified' => 'boolean',
            'is_mobile_verified' => 'boolean',
            'is_seller_verified' => 'boolean',
            'is_blocked' => 'boolean',
            'last_active_at' => 'datetime'
            
        ];
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($user) {
            $user->slug = $user->generateUniqueSlug($user->name);
        });
        
        static::updating(function ($user) {
            if ($user->isDirty('name')) {
                $user->slug = $user->generateUniqueSlug($user->name);
            }
        });
    }
    
    /**
     * Generate a unique slug for the user.
     *
     * @param string|null $name
     * @return string
     */
    public function generateUniqueSlug($name = null)
    {
        // Handle case when name is null or empty
        if (empty($name)) {
            $name = 'user-' . ($this->id ?: rand(1000, 9999));
        }
        
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;
        
        while (static::where('slug', $slug)->where('id', '!=', $this->id ?: 0)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }
        
        return $slug;
    }
    
    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole($role)
    {
        return $this->roles()->where('slug', $role)->exists();
    }

    public function hasPermission($permission)
    {
        return $this->roles()->whereHas('permissions', function($query) use ($permission) {
            $query->where('slug', $permission);
        })->exists();
    }

    /**
     * Get the products that belong to the user.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get user's product limit
     */
    public function getProductLimitAttribute()
    {
        $userSpecificLimit = Setting::getValue('max_products_user_' . $this->id);
        
        if ($userSpecificLimit !== null) {
            return (int)$userSpecificLimit;
        }
        
        return (int)Setting::getValue('max_products_per_user', 10);
    }
    
    /**
     * Get the custom product limit for this user, if any.
     *
     * @return int|null
     */
    public function getProductLimit()
    {
        $setting = \App\Models\Setting::where('key', 'max_products_user_' . $this->id)->first();
        
        return $setting ? $setting->value : null;
    }

    /**
     * Check if user has reached product limit
     */
    public function hasReachedProductLimit()
    {
        // Use direct query instead of relationship loading for accuracy
        $count = Product::where('user_id', $this->id)->count();
        return $count >= $this->product_limit && $this->product_limit != 0;
    }
    
    /**
     * Get remaining products the user can upload
     */
    public function getRemainingProductsAttribute()
    {
        if ($this->product_limit === 0) {
            return PHP_INT_MAX; // Represent unlimited
        }
        
        $currentCount = Product::where('user_id', $this->id)->count();
        return max(0, $this->product_limit - $currentCount);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function isVerified()
    {
        return $this->is_email_verified && $this->is_mobile_verified;
    }

    public function canSell()
    {
        return $this->user_type !== 'buyer' && $this->is_seller_verified;
    }

    public function generateOtp()
    {
        $this->otp = rand(100000, 999999);
        $this->otp_expires_at = now()->addMinutes(10);
        $this->save();
        
        return $this->otp;
    }

    public function verifyOtp($otp)
    {
        return $this->otp === $otp && 
               $this->otp_expires_at && 
               $this->otp_expires_at->isFuture();
    }

    public function incrementSafetyRating()
    {
        $this->safety_rating++;
        $this->successful_transactions++;
        $this->save();
    }

    // Add accessor for full name
    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function unreadMessages()
    {
        return $this->hasMany(Chat::class, 'receiver_id')
            ->where('is_read', false);
    }

    /**
     * Get the university that the user belongs to.
     */
    public function university()
    {
        return $this->belongsTo(University::class);
    }
}

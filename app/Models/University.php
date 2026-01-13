<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'domains',
        'country',
        'alpha_two_code',
        'web_pages',
        'state_province',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the users associated with this university.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the products associated with this university through users.
     */
    public function products()
    {
        return $this->hasManyThrough(Product::class, User::class);
    }
}

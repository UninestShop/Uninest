<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SafeZone extends Model
{
    protected $fillable = [
        'name',
        'university_id',
        'location',
        'description',
        'is_monitored',
        'available_hours',
        'status'
    ];

    protected $casts = [
        'location' => 'array',
        'available_hours' => 'array',
        'is_monitored' => 'boolean'
    ];
}

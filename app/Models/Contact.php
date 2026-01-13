<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'user_id',
        'status'
    ];

    /**
     * Get the user that owns the contact.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CmsPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'status'
    ];

    /**
     * Set the title and generate the url_key (slug).
     *
     * @param  string  $value
     * @return void
     */
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        
        // Only set url_key if it's not already set
        if (!isset($this->attributes['slug']) || empty($this->attributes['slug'])) {
            $this->attributes['slug'] = Str::slug($value);
        }
    }
}

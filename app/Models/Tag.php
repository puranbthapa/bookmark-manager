<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'usage_count',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });

        static::updating(function ($tag) {
            if ($tag->isDirty('name')) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }

    /**
     * Relationships
     */
    public function bookmarks()
    {
        return $this->belongsToMany(Bookmark::class)->withTimestamps();
    }

    /**
     * Scopes
     */
    public function scopePopular($query, $limit = 10)
    {
        return $query->orderBy('usage_count', 'desc')->limit($limit);
    }

    public function scopeAlphabetical($query)
    {
        return $query->orderBy('name');
    }

    /**
     * Increment usage count
     */
    public function incrementUsage()
    {
        $this->increment('usage_count');
    }

    /**
     * Decrement usage count
     */
    public function decrementUsage()
    {
        $this->decrement('usage_count');

        // Remove tag if usage count reaches 0
        if ($this->usage_count <= 0) {
            $this->delete();
        }
    }

    /**
     * Get route key name for URL generation
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}

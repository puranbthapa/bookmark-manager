<?php

/**
 * ================================================================================
 * BOOKMARK MODEL - ELOQUENT ORM
 * ================================================================================
 *
 * 🏢 VENDOR: Eastlink Cloud Pvt. Ltd.
 * 👨‍💻 AUTHOR: Developer Team
 * 📅 CREATED: October 2025
 * 📧 CONTACT: puran@eastlink.net.np
 * 📞 PHONE: +977-01-4101181
 * 📱 DEVELOPER: +977-9801901140
 * 💼 BUSINESS: +977-9801901141
 * 🏢 ADDRESS: Tripureshwor, Kathmandu, Nepal
 *
 * 📋 DESCRIPTION:
 * Core bookmark model with relationships, scopes, and business logic
 * for the advanced bookmark management system.
 *
 * 🎯 FEATURES:
 * - Bookmark CRUD operations
 * - Category and tag relationships
 * - Visit tracking and analytics
 * - Domain extraction accessor
 * - Advanced search scopes
 * - Favorite and privacy controls
 *
 * ⚖️ LICENSE: Commercial Enterprise License
 * ================================================================================
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Bookmark extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'url',
        'description',
        'favicon',
        'thumbnail',
        'category_id',
        'status',
        'favorite',
        'private',
        'visits',
        'short_code',
        'last_checked_at',
        'metadata',
    ];

    protected $casts = [
        'favorite' => 'boolean',
        'private' => 'boolean',
        'visits' => 'integer',
        'last_checked_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($bookmark) {
            if (empty($bookmark->short_code)) {
                $bookmark->short_code = Str::random(8);
            }
        });
    }

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFavorites($query)
    {
        return $query->where('favorite', true);
    }

    public function scopePublic($query)
    {
        return $query->where('private', false);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'LIKE', "%{$term}%")
              ->orWhere('description', 'LIKE', "%{$term}%")
              ->orWhere('url', 'LIKE', "%{$term}%")
              ->orWhereHas('tags', function ($tagQuery) use ($term) {
                  $tagQuery->where('name', 'LIKE', "%{$term}%");
              });
        });
    }

    /**
     * Accessors & Mutators
     */
    public function getDomainAttribute()
    {
        return parse_url($this->url, PHP_URL_HOST);
    }

    public function getPublicUrlAttribute()
    {
        return route('bookmarks.public', $this->short_code);
    }

    /**
     * Increment visit count
     */
    public function incrementVisits()
    {
        $this->increment('visits');
    }
}

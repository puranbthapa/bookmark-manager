<?php

/**
 * ================================================================================
 * USER MODEL - AUTHENTICATION & AUTHORIZATION
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
 * Core user model with authentication, authorization, and relationship
 * management for the multi-user bookmark management system.
 *
 * 🎯 FEATURES:
 * - User authentication & authorization
 * - Role-based access control
 * - Bookmark and category relationships
 * - Activity tracking
 * - Profile management
 * - Security features
 *
 * ⚖️ LICENSE: Commercial Enterprise License
 * ================================================================================
 */

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'settings',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
            'settings' => 'array',
        ];
    }

    /**
     * Relationships
     */
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * Scopes
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }
}

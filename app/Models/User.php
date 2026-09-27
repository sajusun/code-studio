<?php

namespace App\Models;

use App\Traits\HasMedia;
use App\Traits\HasNotifications;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, HasRoles, Notifiable, HasMedia, HasNotifications;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'password',
        'status',
        'last_activity_at',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = ['image'];

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function getImageAttribute(): ?string
    {
        return $this->mediaUrl('avatars', asset('defaults/user-avatar.png'));
    }

    public function isEmailVerified(): bool
    {
        return $this->email_verified_at !== null;
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_activity_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}

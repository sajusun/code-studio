<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Verification extends Model
{
    public const PURPOSE_EMAIL_VERIFICATION = 'email_verification';
    public const PURPOSE_PASSWORD_RESET = 'password_reset';
    public const PURPOSE_PHONE_VERIFICATION = 'phone_verification';

    protected $fillable = [
        'user_id',
        'verification_type',
        'purpose',
        'code',
        'attempts',
        'request_count',
        'last_requested_at',
        'blocked_until',
        'expires_at',
        'verified_at',
        'status',
    ];

    protected $casts = [
        'last_requested_at' => 'datetime',
        'blocked_until' => 'datetime',
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && Carbon::now()->greaterThan($this->expires_at);
    }

    public function isBlocked(): bool
    {
        return $this->blocked_until && Carbon::now()->lessThan($this->blocked_until);
    }

    public function isVerified(): bool
    {
        return $this->status === 'verified' || $this->verified_at !== null;
    }
}

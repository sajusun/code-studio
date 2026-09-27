<?php

namespace App\Models;

use App\Traits\HasMedia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Developer extends Model
{
    use HasMedia;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'bio',
        'github_url',
        'linkedin_url',
        'portfolio_url',
        'twitter_url',
        'skills',
        'experience_years',
        'status',
        'sort_order',
    ];

    protected $appends = ['avatar'];

    protected function casts(): array
    {
        return [
            'skills' => 'array',
            'experience_years' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    // ── Boot ─────────────────────────────────────────────────────────────────

    protected static function booted(): void
    {
        static::creating(function (Developer $dev) {
            if (empty($dev->slug)) {
                $dev->slug = Str::slug($dev->name);
            }
        });

        static::updating(function (Developer $dev) {
            if ($dev->isDirty('name') && !$dev->isDirty('slug')) {
                $dev->slug = Str::slug($dev->name);
            }
        });
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            DeveloperRole::class,
            'developer_developer_role',
            'developer_id',
            'developer_role_id'
        );
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'developer_product',
            'developer_id',
            'product_id'
        )->withPivot('role_in_project', 'sort_order')
         ->withTimestamps();
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    // ── Accessors ─────────────────────────────────────────────────────────────

    public function getAvatarAttribute(): ?string
    {
        return $this->mediaUrl('avatars', asset('defaults/user-avatar.png'));
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class DeveloperRole extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'color',
        'icon',
        'description',
        'sort_order',
    ];

    protected static function booted(): void
    {
        static::creating(function (DeveloperRole $role) {
            if (empty($role->slug)) {
                $role->slug = Str::slug($role->name);
            }
        });

        static::updating(function (DeveloperRole $role) {
            if ($role->isDirty('name') && !$role->isDirty('slug')) {
                $role->slug = Str::slug($role->name);
            }
        });
    }

    public function developers(): BelongsToMany
    {
        return $this->belongsToMany(Developer::class, 'developer_developer_role');
    }
}

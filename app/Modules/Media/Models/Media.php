<?php

namespace App\Modules\Media\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $fillable = [
        'mediable_type',
        'mediable_id',
        'disk',
        'collection_name',
        'original_name',
        'file_name',
        'mime_type',
        'extension',
        'size',
        'path',
        'is_primary',
        'sort_order',
        'status',
        'custom_properties',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'custom_properties' => 'array',
    ];

    protected $appends = ['url'];

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getUrlAttribute(): ?string
    {
        if (!$this->path) {
            return null;
        }

        return Storage::disk($this->disk ?? 'public')->url($this->path);
    }
}

<?php

namespace App\Traits;

use App\Modules\Media\Models\Media;

trait HasMedia
{
    public function media()
    {
        return $this->morphMany(Media::class, 'mediable')->orderBy('sort_order', 'asc');
    }

    public function mediaCollection(string $collection)
    {
        return $this->media()->where('collection_name', $collection);
    }

    public function firstMedia(?string $collection = null)
    {
        return $this->media()->when(
            $collection,
            fn($query) => $query->where('collection_name', $collection)
        )->first();
    }

    public function primaryMedia()
    {
        return $this->media()->where('is_primary', true)->first();
    }

    public function mediaUrl(?string $collection = null, ?string $defaultUrl = null): ?string
    {
        $media = $this->firstMedia($collection);
        return $media?->url ?? $defaultUrl;
    }

    public function hasMedia(?string $collection = null): bool
    {
        return $this->media()
            ->when(
                $collection,
                fn($query) => $query->where('collection_name', $collection)
            )->exists();
    }
}

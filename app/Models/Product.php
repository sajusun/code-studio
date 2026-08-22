<?php

namespace App\Models;

use App\Traits\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, HasMedia;

    protected $fillable = [
        'title',
        'slug',
        'type',
        'category',
        'price',
        'short_description',
        'description',
        'demo_url',
        'admin_demo_url',
        'admin_demo_username',
        'admin_demo_password',
        'video_url',
        'apk_url',
        'testflight_url',
        'thumbnail',
        'screenshots',
        'tech_stack',
        'features',
        'contact_channels',
        'allow_custom_quotes',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'screenshots' => 'array',
        'tech_stack' => 'array',
        'features' => 'array',
        'contact_channels' => 'array',
        'allow_custom_quotes' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->title) . '-' . Str::random(5);
            }
        });
    }

    private function formatMediaUrl(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        $cleanPath = ltrim($path, '/');
        if (Str::startsWith($cleanPath, 'storage/')) {
            return asset($cleanPath);
        }

        if (Str::startsWith($cleanPath, 'public/')) {
            $cleanPath = Str::after($cleanPath, 'public/');
        }

        return asset('storage/' . $cleanPath);
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        $media = $this->firstMedia('thumbnail');
        if ($media) {
            return $this->formatMediaUrl($media->path);
        }

        if (!empty($this->thumbnail)) {
            return $this->formatMediaUrl($this->thumbnail);
        }

        return null;
    }

    public function getGalleryUrlsAttribute(): array
    {
        $galleryMedia = $this->mediaCollection('gallery')->get();
        if ($galleryMedia->isNotEmpty()) {
            return $galleryMedia->map(fn($m) => $this->formatMediaUrl($m->path))->filter()->values()->toArray();
        }

        if (is_array($this->screenshots) && count($this->screenshots) > 0) {
            return array_map(fn($path) => $this->formatMediaUrl($path), array_filter($this->screenshots));
        }

        return [];
    }

    public function getTypeBadgeClassAttribute(): string
    {
        return match ($this->type) {
            'web_app' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
            'android' => 'bg-sky-500/10 text-sky-500 border-sky-500/20',
            'ios' => 'bg-indigo-500/10 text-indigo-500 border-indigo-500/20',
            'custom' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
            default => 'bg-slate-500/10 text-slate-500 border-slate-500/20',
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'web_app' => 'Web App',
            'android' => 'Android App',
            'ios' => 'iOS App',
            'custom' => 'Custom App / Template',
            default => ucfirst($this->type),
        };
    }
}

<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileService
{
    public function upload(UploadedFile $file, string $folder, ?string $disk = null, ?string $name = null): string
    {
        $disk ??= config('filesystems.default', 'public');
        $fileName = ($name ? Str::slug($name) . '-' : '') . Str::uuid() . '.' . $file->getClientOriginalExtension();

        return Storage::disk($disk)->putFileAs($folder, $file, $fileName);
    }

    public function delete(?string $path, ?string $disk = null): bool
    {
        $disk ??= config('filesystems.default', 'public');
        if (!$path) {
            return false;
        }

        $normalizedPath = $this->normalizePath($path);

        if (!Storage::disk($disk)->exists($normalizedPath)) {
            return false;
        }

        return Storage::disk($disk)->delete($normalizedPath);
    }

    public function normalizePath(string $path): string
    {
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            $parsed = parse_url($path);
            $path = ltrim($parsed['path'] ?? '', '/');
        }

        return preg_replace('#^storage/#', '', ltrim($path, '/'));
    }

    public function replace(UploadedFile $file, ?string $oldPath, string $folder, ?string $disk = null, ?string $name = null): string
    {
        $disk ??= config('filesystems.default', 'public');
        $this->delete($oldPath, $disk);

        return $this->upload($file, $folder, $disk, $name);
    }

    public function url(?string $path, ?string $disk = null): ?string
    {
        $disk ??= config('filesystems.default', 'public');
        return $path ? Storage::disk($disk)->url($path) : null;
    }
}

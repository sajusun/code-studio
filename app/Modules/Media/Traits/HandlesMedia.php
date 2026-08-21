<?php

namespace App\Modules\Media\Traits;

use App\Modules\Media\Models\Media;
use App\Services\FileService;

trait HandlesMedia
{
    protected function uploadMedia($model, $files, $collection = 'default', $disk = null)
    {
        $files = is_array($files) ? $files : [$files];
        $uploadedMedia = [];
        $disk ??= config('filesystems.default', 'public');
        $fileService = app(FileService::class);

        foreach ($files as $file) {
            if (!$file) {
                continue;
            }

            $path = $fileService->upload($file, $collection, $disk);

            $media = $model->media()->create([
                'disk' => $disk,
                'collection_name' => $collection,
                'original_name' => $file->getClientOriginalName(),
                'file_name' => basename($path),
                'mime_type' => $file->getMimeType(),
                'extension' => $file->getClientOriginalExtension(),
                'size' => $file->getSize(),
                'path' => $path,
                'status' => 'active',
            ]);

            $uploadedMedia[] = $media;
        }

        return collect($uploadedMedia);
    }

    protected function updateMedia($model, $files, $collection = 'default', $disk = null)
    {
        $disk ??= config('filesystems.default', 'public');

        $existingMedia = $model->media()->where('collection_name', $collection)->get();

        if ($existingMedia->isNotEmpty()) {
            $this->deleteMedia($existingMedia->pluck('id')->toArray());
        }

        return $this->uploadMedia($model, $files, $collection, $disk);
    }

    protected function deleteMedia(int|array $ids): bool
    {
        $ids = is_array($ids) ? $ids : [$ids];
        $mediaItems = Media::whereIn('id', $ids)->get();

        if ($mediaItems->isEmpty()) {
            return false;
        }

        $fileService = app(FileService::class);

        foreach ($mediaItems as $media) {
            if ($media->path) {
                $fileService->delete($media->path, $media->disk);
            }
            $media->delete();
        }

        return true;
    }
}

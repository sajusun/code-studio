<?php

namespace App\Modules\Media\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Media\Models\Media;
use App\Modules\Media\Traits\HandlesMedia;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    use ApiResponse, HandlesMedia;

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|max:20480', // Max 20MB
            'collection' => 'nullable|string',
            'disk' => 'nullable|string',
        ]);

        $disk = $request->input('disk', config('filesystems.default', 'public'));
        $collection = $request->input('collection', 'default');
        $file = $request->file('file');

        $fileService = app(\App\Services\FileService::class);
        $path = $fileService->upload($file, $collection, $disk);

        $media = Media::create([
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

        return self::success('Media uploaded successfully', $media, 201);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->deleteMedia($id);

        if (!$deleted) {
            return self::error('Media not found or could not be deleted', 404);
        }

        return self::success('Media deleted successfully');
    }
}

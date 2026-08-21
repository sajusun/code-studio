<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseMediaController;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomMediaController extends BaseMediaController
{
    /**
     * Upload primary image for a room (single file).
     */
    public function uploadPrimary(Request $request, Room $room)
    {
        $request->validate([
            'file' => 'required|image|max:5120', // 5MB max
        ]);

        return $this->uploadMedia($room, $request->file('file'), 'primary');
    }

    /**
     * Upload gallery images for a room (multiple files, up to 4).
     */
    public function uploadGallery(Request $request, Room $room)
    {
        $request->validate([
            'files' => 'required|array|max:4',
            'files.*' => 'image|max:5120',
        ]);

        return $this->uploadMedia($room, $request->file('files'), 'gallery');
    }
}

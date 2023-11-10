<?php

namespace App\Services;

use App\Models\Media;
use App\Models\Mediable;

class MediaService
{
    public function store($inputs)
    {
        $mediaIds = []; // Array to store the IDs of the uploaded media

        $index = 1;
        $timestamp = now()->timestamp;

        foreach ($inputs->image as $image) {
            $imageName = $image->getClientOriginalName();
            $name = pathinfo($imageName, PATHINFO_FILENAME);
            $uniqueName = $timestamp . '_' . $name . '_' . $index;
            $uniqueFileName = $uniqueName . '.' . $image->getClientOriginalExtension();

            // Create and save the media record
            $media = Media::create([
                'disk' => 'public',
                'directory' => 'products',
                'filename' => $uniqueName,
                'extension' => $image->getClientOriginalExtension(),
                'mime_type' => $image->getClientMimeType(),
                'size' => $image->getSize(),
            ]);

            $image->move(public_path('/storage/product'), $uniqueFileName);
            $mediaIds[] = $media->id; // Store the ID in the array
            $index++;
        }
        return response()->json(['message' => 'Image Uploaded successfully', 'images' => $mediaIds, 'success' => true], 200);
    }

    public function destroy($media)
    {
        $media = Media::where('ulid', $ulid)->first();
        if(!$media){
            return response()->json(['message' => 'Media not found.','success' => false],404);
        }
        $filePath = public_path('storage/product/' . $media->filename . '.' . $media->extension);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        $media->delete();

        return response()->json(['message' => 'Media deleted successfully','success' => true],200);
    }
}

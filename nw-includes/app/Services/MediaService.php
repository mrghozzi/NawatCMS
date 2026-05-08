<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

final class MediaService
{
    private string $disk = 'nawat_uploads';

    /**
     * Upload a file and create a Media record.
     */
    public function upload(UploadedFile $file, ?string $altText = null): Media
    {
        // For v1.0, we just store it in the root of the uploads directory.
        // A more advanced system might organize by year/month.
        $path = $file->store('/', $this->disk);

        return Media::create([
            'filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'path' => $path,
            'alt_text' => $altText,
        ]);
    }

    /**
     * Delete a media item and its physical file.
     */
    public function delete(Media $media): void
    {
        Storage::disk($this->disk)->delete($media->path);
        $media->delete();
    }
}

<?php

namespace App\Repositories;

use App\Enums\MediaTypeEnum;
use App\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;


class MediaRepository extends BaseRepository
{
    protected Model $model;

    public function __construct(Media $model)
    {
        $this->model = $model;
    }

    public function upload(int $postId, UploadedFile $file): ?Model
    {
        if (!$file->isValid()) {
            return null;
        }

        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $fileName = uniqid(time()) . '.' . $extension;

        $filePath = Storage::disk('posts')->putFileAs("/posts", $file, $fileName);

        if (!$filePath) {
            return null;
        }
        $mediaType = match ($extension) {
            'jpg', 'jpeg', 'png', 'gif' => MediaTypeEnum::PICTURE,
            'mp4', 'mov', 'avi' => MediaTypeEnum::VIDEO,
            default => throw new InvalidArgumentException('Unsupported file type')
        };
        $payload = [
            "post_id" => $postId,
            "file_path" => $postId,
            "media_type" => $mediaType,
        ];

        return $this->create($payload);
    }

    /**
     * Delete a media file.
     *
     * @param Media $media Media record to delete
     * @return bool True on success, false on failure
     */
    public function delete(Media $media): bool
    {
        if (Storage::disk('posts')->exists($media->file_path)) {
            Storage::disk('posts')->delete($media->file_path);
        }

        return $media->delete();
    }
}

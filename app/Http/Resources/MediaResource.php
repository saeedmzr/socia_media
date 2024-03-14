<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @OA\Schema(
 *     schema="MediaSchema",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Media ID"
 *     ),
 *      @OA\Property(
 *         property="file_path",
 *         type="string",
 *         description="Media file_path"
 *     ),
 *     @OA\Property(
 *         property="media_type",
 *         type="string",
 *         description="Media media_type"
 *     ),
 *          @OA\Property(
 *          property="created at",
 *          type="datetime",
 *          description="Media created_at datetime"
 *      ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="datetime",
 *         description="Media updated_at datetime"
 *     ),
 * )
 */
class MediaResource extends JsonResource
{


    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "file_path" => $this->file_path,
            "media_type" => $this->media_type,
            "updated_at" => $this->updated_at,
            "created_at" => $this->created_at,
        ];
    }
}

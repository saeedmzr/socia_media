<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @OA\Schema(
 *     schema="CommentSchema",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Comment ID"
 *     ),
 *      @OA\Property(
 *         property="body",
 *         type="string",
 *         description="Comment body"
 *     ),
 *          @OA\Property(
 *          property="created at",
 *          type="datetime",
 *          description="Comment created_at datetime"
 *      ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="datetime",
 *         description="Comment updated_at datetime"
 *     ),
 * )
 */
class CommentResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "file_path" => $this->body,
            "updated_at" => $this->updated_at,
            "created_at" => $this->created_at,
        ];
    }
}

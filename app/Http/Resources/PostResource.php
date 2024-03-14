<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @OA\Schema(
 *     schema="PostSchema",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Post ID"
 *     ),
 *      @OA\Property(
 *         property="content",
 *         type="string",
 *         description="Post content"
 *     ),
 *     @OA\Property(
 *         property="visibility",
 *         type="string",
 *         description="Post visibility"
 *     ),
 *          @OA\Property(
 *          property="created at",
 *          type="datetime",
 *          description="Post created_at datetime"
 *      ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="datetime",
 *         description="Post updated_at datetime"
 *     ),
 * )
 */
class PostResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        $data = [
            "id" => $this->id,
            "content" => $this->content,
            "visibility" => $this->visibility,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];

        // Check if request is for a single item
        if ($this->isSingleResource()) {
            $data["likes_count"] = $this->likes->count();
            $data["comments"] = CommentResource::collection($this->comments);
            $data["media"] = MediaResource::collection($this->media);
        }

        return $data;
    }

    /**
     * Determine if the current resource is being represented as a single item.
     *
     * @return bool
     */
    protected function isSingleResource(): bool
    {
        // You can modify this logic based on your specific needs
        // Here, we check if the resource URI contains an ID
        return $this->uri !== url(route('posts.index'));
    }
}

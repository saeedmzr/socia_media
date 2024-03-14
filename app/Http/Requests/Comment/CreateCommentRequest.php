<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="CreateCommentSchema",
 *     @OA\Property(
 *          property="body",
 *          type="string",
 *          description="comment's body"
 *      ),
 *          @OA\Property(
 *           property="post_id",
 *           type="integer",
 *           description="comment's post id"
 *       )
 * )
 */
class CreateCommentRequest extends FormRequest
{

    public function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => auth()->id(),
        ]);
    }

    public function rules(): array
    {
        return [
            'user_id' => '',
            'post_id' => ['required', "exists:posts,id"],
            'body' => ["required", "string"],

        ];
    }
}

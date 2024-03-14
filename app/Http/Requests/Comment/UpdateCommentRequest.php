<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="UpdateCommentSchema",
 *     @OA\Property(
 * * *         property="body",
 * * *         type="string",
 * * *         description="comment's body"
 * * *     ),
 * )
 */
class UpdateCommentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'body' => ["required", "string"],
        ];
    }
}

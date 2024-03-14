<?php

namespace App\Http\Requests\Post;

use App\Enums\MediaTypeEnum;
use App\Enums\PostVisibilityEnum;
use App\Models\Media;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

/**
 * @OA\Schema(
 *     schema="CreatePostSchema",
 *     @OA\Property(
 *      property="content",
 *      type="string",
 *      description="post's content"
 *  ),
 *  @OA\Property(
 *      property="visibility",
 *      type="string",
 *      description="post's visibility"
 *  ),
 *               @OA\Property(
 *           property="media",
 *           type="string",
 *           description="post's media"
 *       ),
 * )
 */
class CreatePostRequest extends FormRequest
{

    public function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => auth()->id(),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => '',
            'content' => ["required", "string"],
            'visibility' => ['nullable', 'in:' . implode(',', PostVisibilityEnum::all())],
            'media' => ['nullable', 'array'],
            'media.file' => ['nullable', new File],
            'media.type' => ['nullable', "string", 'in:' . implode(',', MediaTypeEnum::all())],
        ];
    }
}

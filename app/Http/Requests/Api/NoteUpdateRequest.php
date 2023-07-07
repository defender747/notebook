<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 * title="Update notebook request",
 * description="Update notebook request body data",
 * type="object",

 * @OA\Property(
 *     title="full_name",
 *     property="full_name",
 *     description="fio of the new notebook",
 *     example="Sheldon Lee Cooper",
 *     type="string"
 * ),
 * @OA\Property(
 *     title="company",
 *     property="company",
 *     description="Company name",
 *     example="Future",
 *     type="string"
 * ),
 * @OA\Property(
 *     title="phone",
 *     property="phone",
 *     description="phone number",
 *     example="+39138789252",
 *     type="string"
 * ),
 * @OA\Property(
 *     title="email",
 *     property="email",
 *     description="email",
 *     example="user@mail.ru",
 *     type="string"
 * ),
 * @OA\Property(
 *     title="birth_date",
 *     property="birth_date",
 *     description="date of birth",
 *     example="1995-01-25",
 *     type="Date"
 * ),
 * @OA\Property(
 *     title="photo_url",
 *     property="photo_url",
 *     description="photo_url",
 *     example="http:://notebook.ru/public/storage/qwerty-photo.jpeg",
 *     type="string"
 * ),
 * @OA\Property(
 *     title="photo_must_deleted",
 *     property="photo_must_deleted",
 *     description="should the photo be deleted",
 *     example="false",
 *     type="boolean"
 * ),
 * @OA\Property(
 *     title="photo_file",
 *     property="photo_file",
 *     description="binary photo file, max size 128 KiB",
 *     type="string",
 *     format="binary"
 * )
 * )
 */
class NoteUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'full_name' => ['min:2', 'max:255'],
            'company' => ['string', 'max:255'],
            'phone' => ['min:3', 'max:50',
                Rule::unique('notes', 'phone')->ignore($this->id)],
            'email' => ['email', 'max:255',
                Rule::unique('notes', 'email')->ignore($this->id)],
            'birth_date' => ['date'],

            'photo_url' => ['string', 'max:255'],
            'photo_must_deleted' => ['boolean'],
            'photo_file' => ['file', 'nullable', 'mimes:jpeg,webp,webm,bmp,svg,jpg,png|max:128'],
        ];
    }
}

<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
* @OA\Schema(
*  title="Get all notes request with paginate",
*  description="Get all notes request with paginate",
*  type="object",

 * @OA\Property(
 *      title="per_page",
 *      property="per_page",
 *      description="count notes show per page",
 *      example="5",
 *      type="integer"
 * ),
 * @OA\Property(
 *      title="cursor",
 *      property="cursor",
 *      description="cursor",
 *      example="eyJpZCI6MywiX3BvaW50c1RvTmV4dEl0ZW1zIjp0cnVlfQ",
 *      type="string"
 * )
 * )
 *
 * @property int $per_page
 * @property string $next_cursor
// * @property int $page
 */
class NoteListGetRequest extends FormRequest
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
            'per_page' => ['integer'],
            'cursor' => ['string'],
        ];
    }
}

<?php

namespace App\Http\Resources\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 * title="Get one notebook Resource",
 * description="Get one notebook Resource body data toArray",
 * type="object",
 * required={"name"}
 * ),
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
 *     title="photo_need_delete",
 *     property="photo_need_delete",
 *     description="is photo need to deleted",
 *     example="false",
 *     type="boolean"
 * ),
 * @OA\Property(
 *     title="created_at",
 *     property="created_at",
 *     description="created_at",
 *     example="2023-07-04 17:50:51",
 *     type="Carbon"
 * ),
 * @OA\Property(
 *     title="updated_at",
 *     property="updated_at",
 *     description="updated_at",
 *     example="2023-07-04 17:50:51",
 *     type="Carbon"
 * )
 *
 * @property int $id
 * @property string $full_name
 * @property string|null $company
 * @property string $phone
 * @property string $email
 * @property Carbon|null $birth_date
 * @property string $photo_url
 * @property bool $photo_need_delete
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class NoteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'company' => $this->company,
            'phone' => $this->phone,
            'email' => $this->email,
            'birth_date' => $this->birth_date,

            'photo_url' => $this->photo_url,
            'photo_need_delete' => $this->photo_need_delete ?? false,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

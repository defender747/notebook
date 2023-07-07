<?php

namespace App\Http\Resources\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @OA\Schema(
 * title="Get notebook collection",
 * description="Get notebook collection body data toArray",
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
 *     title="photo_must_deleted",
 *     property="photo_must_deleted",
 *     description="should the photo be deleted",
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
 * @property bool $photo_must_deleted
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class NoteCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array<int|string, mixed>
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
            'photo_must_deleted' => $this->photo_must_deleted,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

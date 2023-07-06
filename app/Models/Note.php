<?php

namespace App\Models;

use App\Services\FileUploadService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *   title="Notebook",
 *   description="Notebook model",
 *   @OA\Xml(
 *      name="Notebook"
 *   ),
 *   required={"full_name", "phone", "email"},
 *
 * @OA\Property(
 *      title="id",
 *      property="id",
 *      description="id",
 *      example=1,
 *      type="integer"
 * ),
 * @OA\Property(
 *      title="full_name",
 *      property="full_name",
 *      description="fio of the new notebook",
 *      example="Sheldon Lee Cooper",
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
 *     title="photo_uuid",
 *     property="photo_uuid",
 *     description="photo_uuid",
 *     example="d4d80caa-381c-4743-b320-4d55ef8c57b3",
 *     type="string"
 * ),
 * @OA\Property(
 *     title="photo_name",
 *     property="photo_name",
 *     description="photo_name",
 *     example="storage/photo.jpg",
 *     type="string"
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
 * )
 *
 * @property int $id
 * @property string $full_name
 * @property string|null $company
 * @property string $phone
 * @property string $email
 * @property Carbon|null $birth_date
 * @property string $photo_uuid like "d4d80caa-381c-4743-b320-4d55ef8c57b3"
 * @property string $photo_name like "photo.jpg"
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Note extends Model
{
    use HasFactory;

    public const TABLE = 'notes';

    /** @var string */
    protected $table = self::TABLE;

    /** @var bool */
    public $timestamps = true;

    /** @var array */
    protected $fillable = [
        'id',
        'full_name',
        'company',
        'phone',//unique
        'email',//unique
        'birth_date',
        'photo_uuid',
        'photo_name',
    ];

    /** @var array */
    protected $casts = [
        'id' => 'integer',
        'full_name' => 'string',
        'company' => 'string',
        'phone' => 'string',
        'email' => 'string',
        'birth_date' => 'date:Y-m-d',
        'photo_uuid' => 'string',
        'photo_name' => 'string',
    ];

    /** @var array */
    protected $appends = [
        'photo_url',
    ];

    /**
     * return like 'http://dev.notebook.ru/storage/photo.jpg'
     * @return String|null
     */
    public function getPhotoUrlAttribute(): ?string
    {
        $path = FileUploadService::getFilePath($this->photo_uuid, $this->photo_name);

        return ($this->photo_uuid && $this->photo_name)
            ? asset(Storage::url($path))
            : null;
    }

    /**
     * @param $value
     * @return string
     */
    public function getBirthDateAttribute($value): string
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    /**
     * @param $value
     * @return string
     */
    public function getCreatedAtAttribute($value): string
    {
        return Carbon::parse($value)
            ->setTimezone(config('app.timezone'))
            ->format('Y-m-d H:i:s');
    }

    /**
     * @param $value
     * @return string
     */
    public function getUpdatedAtAttribute($value): string
    {
        return Carbon::parse($value)
            ->setTimezone(config('app.timezone'))
            ->format('Y-m-d H:i:s');
    }
}

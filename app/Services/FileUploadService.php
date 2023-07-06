<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class FileUploadService
{
    private const STORAGE_LINK = 'public/notebook';
    private const FILE_PATH_TEMPLATE = '%s/%s_%s';

    /**
     * @param UploadedFile $file
     * @param string $newFilePath
     * @return string
     */
    public function upload(UploadedFile $file, string $newFilePath): string
    {
        return $file->storeAs($newFilePath);
    }

    /**
     * @param string $filePath
     * @return void
     */
    public function delete(string $filePath): void
    {
        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
        }
    }

    /**
     * @param string|null $fileUid
     * @param string|null $fileName
     * @return string
     */
    public static function getFilePath(?string $fileUid, ?string $fileName): string
    {
        return sprintf(self::FILE_PATH_TEMPLATE,
            self::STORAGE_LINK,
            $fileUid,
            $fileName
        );
    }

    /**
     * @return string
     */
    public static function getFileUuid(): string
    {
        return Uuid::uuid4()->toString();
    }
}

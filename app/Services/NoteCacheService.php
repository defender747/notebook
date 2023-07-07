<?php

namespace App\Services;

use App\Models\Note;
use Illuminate\Support\Facades\Cache;

class NoteCacheService extends CacheService
{
    public const KEY_NOTE = 'note';

    public function getKey(array $key): string
    {
//        $key = $this->clearAndGetKey($key);
        return $this->implodeArrayKey($key);
    }

    public function getKeyByModel(Note $note): string
    {
        return $this->implodeArrayKey([
            self::KEY_NOTE,
                $note->id,
                $note->phone ?? "",
                $note->email ?? ""
        ]);
    }

    public function getKeyByArray(array $note): string
    {
        return $this->implodeArrayKey([
            self::KEY_NOTE,
                $note['id'] ?? "",
                $note['phone'] ?? "",
                $note['email'] ?? ""
        ]);
    }

    public function getFromCache(string $key, mixed $default = null): mixed
    {
        return Cache::get($key, $default);
    }

    public function cacheAllDataByRequiredFields(): mixed
    {
        Note::query()
            ->cursor()
            ->each(function ($note) {
                return $this->rememberToCache(
                    $this->getKeyByModel($note),
                    $note
                );
            });

        return $this->getAllCacheData();
    }

    public function getAllCacheData(): mixed
    {
        return Cache::get(self::KEY_NOTE);
    }


    //todo: to CLI command
    public function warmUp(): void
    {
        $this->cacheAllDataByRequiredFields();
    }

    //todo: to CLI command
    public function cacheClear(): void
    {
        $this->deleteFromCache(self::KEY_NOTE);
    }
}

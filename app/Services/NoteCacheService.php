<?php

namespace App\Services;

use App\Models\Note;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class NoteCacheService extends CacheService
{
    /** @var string */
    public const KEY_NOTE = 'note';

    /**
     * При добавлении в ключ условий по полям
     * может потребоваться принудительный сброс всего кэша
     * если запись будет удалена из базы без удаления из кэш
     *
     * @param Model $model
     * @return string
     */
    public function getKey(Model $model): string
    {
        return implode(self::KEY_SEPARATOR,
            collect([self::KEY_NOTE, $model->id ?? ""])
                ->reject(function ($k) {
                    return empty($k);
                })
                ->toArray()
        );
    }

    /**
     * @param int $id
     * @return string
     */
    public function getKeyById(int $id): string
    {
        return implode(self::KEY_SEPARATOR,
            collect([self::KEY_NOTE, $id])
                ->toArray()
        );
    }

    /**
     * @return mixed
     */
    public function getAllCacheData(): mixed
    {
        return Cache::get(self::KEY_NOTE);
    }

    /**
     * @return mixed
     */
    public function cacheAllDataByRequiredFields(): mixed
    {
        Note::query()
            ->cursor()
            ->each(function ($note) {
                return $this->setToCache($note);
            });

        return $this->getAllCacheData();
    }

    /**
     * todo: to CLI command
     * @return void
     */
    public function warmUp(): void
    {
        $this->cacheAllDataByRequiredFields();
    }

    /**
     * todo: to CLI command
     * @return void
     */
    public function cacheClear(): void
    {
        $this->forget(self::KEY_NOTE);
    }
}

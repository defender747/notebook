<?php

namespace App\Services;

use App\Models\Note;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class NoteCacheService implements CacheService
{
    public const KEY_NOTE = 'note';

    /**
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

    public function getAllCacheData(): mixed
    {
        return Cache::get(self::KEY_NOTE);
    }

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
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function getFromCache(string $key, mixed $default = null): mixed
    {
        return Cache::get($key, $default);
    }

    /**
     * @param Model $model
     * @return mixed
     */
    public function rememberToCache(Model $model): mixed
    {
        $key = $this->getKey($model);
        $this->forget($key);

        return Cache::tags(self::KEY_NOTE)
            ->remember(
                $key,
                self::getTTL(),
                static function () use ($model) {
                    return $model;
                });
    }

    /**
     * @param Model $model
     * @return bool
     */
    public function setToCache(Model $model): bool
    {
        return Cache::tags(self::KEY_NOTE)
            ->put(
                $this->getKey($model),
                $model,
                self::getTTL(),
            );
    }

    /**
     * @param Model $model
     * @return void
     */
    public function forgetFromCache(Model $model): void
    {
        $this->forget($this->getKey($model));
    }

    /**
     * @param int $id
     * @return void
     */
    public function forgetById(int $id): void
    {
        $this->forget($this->getKeyById($id));
    }

    /**
     * @param $key
     * @return void
     */
    public function forget($key): void
    {
        Cache::tags(self::KEY_NOTE)->forget($key);
    }

    /**
     * @param int|null $hours
     * @return CarbonInterval
     */
    public static function getTTL(?int $hours = 3): CarbonInterval
    {
        return CarbonInterval::hours($hours);
    }

    public function warmUp(): void
    {
        $this->cacheAllDataByRequiredFields();
    }

    public function clear(): void
    {
        Cache::tags(self::KEY_NOTE)->flush();
    }
}

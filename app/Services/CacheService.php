<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

abstract class CacheService
{
    /** @var string */
    public const KEY_SEPARATOR = ':';

    /**
     * @param Model $model
     * @return string
     */
    abstract protected function getKey(Model $model): string;

    /**
     * @return mixed
     */
    abstract protected function getAllCacheData(): mixed;

    /**
     * @return void
     */
    abstract protected function warmUp(): void;

    /**
     * @return void
     */
    abstract protected function cacheClear(): void;

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

        return Cache::remember(
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
        return Cache::set(
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
        Cache::forget($key);
    }

    /**
     * @param $key
     * @return array
     */
    public static function clearAndGetKey($key): array
    {
        return array_filter($key, static function ($k) {
            return str_replace(
                ['.', '{', '}', '(', ')', '/', '\\'],
                ['---', '__', '___', '____', '_____', '______', '_______'],
                $k
            );
        });
    }

    /**
     * @param int|null $hours
     * @return int
     */
    public static function getTTL(?int $hours = 3): int
    {
//        return Carbon::now()->add($hours)->second;
        return 1080000000000;
    }
}

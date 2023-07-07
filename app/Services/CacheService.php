<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

interface CacheService
{
    public const KEY_SEPARATOR = ':';

    public function getKey(Model $model): string;

    public function getKeyById(int $id): string;


    public function getAllCacheData(): mixed;

    public function getFromCache(string $key, mixed $default = null): mixed;

    public function rememberToCache(Model $model): mixed;

    public function setToCache(Model $model): bool;


    public function forgetFromCache(Model $model): void;

    public function forgetById(int $id): void;

    public function forget($key): void;


    public function warmUp(): void;

    public function clear(): void;
}

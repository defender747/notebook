<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

abstract class CacheService
{
    public const DEFAULT_TTL = 10800; // 3h
    public const KEY_SEPARATOR = ':';

    public function rememberToCache(string $key, mixed $value, $ttl = self::DEFAULT_TTL): mixed
    {
        return Cache::remember(
            $key,
            $ttl,
            static function () use ($value) {
                return $value;
            });
    }

    public function setToCache(string $key, $value, $ttl = self::DEFAULT_TTL): void
    {
        Cache::set(
            $key,
            $value,
            $ttl
        );
    }

    public function getFromCache(string $key, mixed $default = null): mixed
    {
        return Cache::get($key, $default);
    }

    public function deleteFromCache($key): void
    {
        Cache::forget($key);
    }

    public function implodeArrayKey(array $key): string
    {
        return implode(self::KEY_SEPARATOR, $key);
    }

    public function clearAndGetKey($key): array
    {
        return array_filter($key, static function ($k) {
            return str_replace(
                ['.', '{', '}', '(', ')', '/', '\\'],
                [ '---', '__', '___', '____', '_____', '______', '_______'],
                $k
            );
        });
    }

    abstract protected function getKey(array $key): string;
    abstract protected function warmUp(): void;
    abstract protected  function getAllCacheData(): mixed;
    abstract protected function cacheClear(): void;
}

<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    public const DEFAULT_TTL = 10800; // 3h
    public const TTL_DAY = 86400;
    private string $key;

    public function getKey(string $phone, string $email): string
    {
        return $this->key;
    }

    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    public function rememberToCache($key, $value, $ttl = self::DEFAULT_TTL)
    {
        $key = $this->clearAndGetKey($key);

        return Cache::remember(
            $key,
            $ttl,
            static function () use ($value) {
                return $value;
            });
    }

    public function setToCache($key, $value, $ttl = self::DEFAULT_TTL)
    {
        $key = $this->clearAndGetKey($key);

        Cache::set(
            $key,
            $value,
            $ttl
        );
    }

    private function clearAndGetKey($key): string
    {
        return str_replace(
            ['__', '___', '____', '_____', '______', '_______', '________', '---'],
            [':', '{', '}', '(', ')', '/', '\\', '@'],
            $key
        );
    }

    public function warmUp()
    {
        //TODO
    }

    public function getFromCache($key, $default)
    {
        return Cache::get($key, $default);
    }

    public function hasInCache($key)
    {
        //TODO
    }

    public function deleteFromCache($key): void
    {
        Cache::forget($key);
    }
}

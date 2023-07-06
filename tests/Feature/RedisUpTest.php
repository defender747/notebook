<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Ramsey\Uuid\Nonstandard\Uuid;
use Tests\TestCase;

class RedisUpTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        self::assertTrue(Redis::connection()->ping());
    }

    public function test_cache_put(): void
    {
        $id = 100;
        $key = Uuid::uuid4() . $id;

        $put = Cache::put($key, $id);
        $get = Cache::get($key);

        self::assertEquals($put, $get);
    }
}

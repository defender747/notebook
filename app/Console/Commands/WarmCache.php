<?php

namespace App\Console\Commands;

use App\Services\NoteCacheService;
use Illuminate\Console\Command;

class WarmCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:warm-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Warm cache';


    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        /** @var NoteCacheService $noteCacheService */
        $noteCacheService = app(NoteCacheService::class);

        $noteCacheService->warmUp();
    }
}

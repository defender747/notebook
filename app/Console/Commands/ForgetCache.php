<?php

namespace App\Console\Commands;

use App\Services\NoteCacheService;
use Illuminate\Console\Command;

class ForgetCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:forget-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Forget cache';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /** @var NoteCacheService $noteCacheService */
        $noteCacheService = app(NoteCacheService::class);

        $noteCacheService->clear();
    }
}

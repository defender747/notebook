<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Throwable;

class Handler extends ExceptionHandler
{
    /**O
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            $this->logError($e);
        });
    }

    /**
     * @param Throwable $e
     */
    public static function logError(Throwable $e): void
    {
        Log::error(
            sprintf('[%s] %s %s:%s',
                get_class($e),
                $e->getMessage(),
                $e->getFile(),
                $e->getLine())
        );
    }

    /**
     * @param Throwable $e
     * @return array
     */
    public static function getError(Throwable $e): array
    {
        return (new BaseError())
            ->setStatus($e->getCode())
            ->setMessage($e->getMessage())
            ->toArray();
    }
}

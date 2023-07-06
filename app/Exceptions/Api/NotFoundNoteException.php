<?php


namespace App\Exceptions\Api;

use App\Exceptions\Handler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NotFoundNoteException extends NotFoundHttpException
{
    /**
     * @return int
     */
    protected function getErrorCode(): int
    {
        return Response::HTTP_NOT_FOUND;
    }

    /**
     * @return string
     */
    protected function getErrorMessage(): string
    {
        return trans('error.not_found_note');
    }

    /**
     * NotFoundNoteException constructor.
     */
    public function __construct()
    {
        parent::__construct(
            $this->getErrorMessage(),
            null,
            $this->getErrorCode()
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function render(Request $request): JsonResponse
    {
        Handler::logError($this);

        return response()->json(
            Handler::getError($this),
            $this->getErrorCode()
        );
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Throwable;

abstract class Controller extends BaseController
{
    public const MESSAGE_ITEM_NOT_FOUND = 'Item not found';
    public const MESSAGE_WRONG_REQUEST  = 'Wrong request';

    protected function wrongRequestResponse(Throwable $exception): JsonResponse
    {
        return $this->jsonResponse(self::MESSAGE_WRONG_REQUEST . ': ' . $exception->getMessage(), HttpResponse::HTTP_BAD_REQUEST);
    }

    protected function jsonResponse($message, $code): JsonResponse
    {
        return Response::json(['message' => $message], $code);
    }
}

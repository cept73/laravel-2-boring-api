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

    protected function itemNotFoundResponse(): JsonResponse
    {
        return Response::json(['message' => self::MESSAGE_ITEM_NOT_FOUND], HttpResponse::HTTP_NOT_FOUND);
    }

    protected function serverErrorResponse(Throwable $exception): JsonResponse
    {
        return Response::json(['message' => $exception->getMessage()], HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    protected function wrongRequestResponse(): JsonResponse
    {
        return Response::json(['message' => self::MESSAGE_WRONG_REQUEST], HttpResponse::HTTP_BAD_REQUEST);
    }
}

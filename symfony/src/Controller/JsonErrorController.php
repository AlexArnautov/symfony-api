<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class JsonErrorController
{
    public function show(Throwable $exception): JsonResponse
    {
        $code = Response::HTTP_INTERNAL_SERVER_ERROR;

        if ($exception instanceof HttpExceptionInterface) {
            $code = $exception->getStatusCode();
        }

        return new JsonResponse([
            'error' => [
                'message' => $exception->getMessage(),
                'code' => $code
            ]
        ]);
    }
}
<?php

declare(strict_types=1);

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ResponseFormatter
{
    public function contentResponse(array $content, int $statusCode): JsonResponse
    {
        return new JsonResponse(
            data: [
                'data' => $content
            ],
            status: $statusCode
        );
    }

    public function noContentResponse(): JsonResponse
    {
        return new JsonResponse(
            data: null,
            status: Response::HTTP_NO_CONTENT
        );
    }
}

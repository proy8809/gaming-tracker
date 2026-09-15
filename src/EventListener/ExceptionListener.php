<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Services\Api\DomainException;
use App\Services\Api\SteamClient\SteamException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Throwable;

class ExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $event->setResponse(
            match (get_class($event->getThrowable())) {
                SteamException::class => $this->handleSteamException($event->getThrowable()),
                DomainException::class => $this->handleDomainException($event->getThrowable()),
                default => $this->handleGenericException($event->getThrowable()),
            }
        );

        $response = new JsonResponse([
            'message' => $event->getThrowable()->getMessage(),
            'code' => $event->getThrowable()->getCode(),
            'trace' => $event->getThrowable()->getTraceAsString(),
        ], $event->getThrowable()->getCode(), ['Content-Type' => 'application/json'], true);

        $event->setResponse($response);
    }

    private function handleSteamException(Throwable $exception): JsonResponse
    {
        return new JsonResponse([
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'trace' => $exception->getTraceAsString(),
        ], $exception->getCode(), ['Content-Type' => 'application/json'], true);
    }

    private function handleDomainException(Throwable $exception): JsonResponse
    {
        return new JsonResponse([
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'trace' => $exception->getTraceAsString(),
        ], $exception->getCode(), ['Content-Type' => 'application/json'], true);
    }

    private function handleGenericException(Throwable $exception): JsonResponse
    {
        return new JsonResponse([
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'trace' => $exception->getTraceAsString(),
        ], Response::HTTP_INTERNAL_SERVER_ERROR, ['Content-Type' => 'application/json'], true);
    }
}

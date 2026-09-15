<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Services\Api\Synchronization\SteamGamesSynchronizerInterface;
use App\Services\Api\User\SteamUserExtractorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: "users", name: "api_games_")]
final readonly class UserController
{
    use ResponseFormatter;

    public function __construct(
        private SteamUserExtractorInterface $userExtractor,
        private SteamGamesSynchronizerInterface $gamesSynchronizer,
    ) {
    }

    #[Route(path: "/{steamUserId}", name: "get_user", methods: ["GET"], requirements: ['steamUserId' => '\d+'])]
    public function getUser(int $steamUserId): JsonResponse
    {
        $user = $this->userExtractor->execute($steamUserId);

        return $this->contentResponse($user->toArray(), Response::HTTP_OK);
    }

    #[Route(path: "/{steamUserId}/synchronizations", name: "create_synchronization", methods: ["POST"], requirements: ['steamUserId' => '\d+'])]
    public function createSynchronization(int $steamUserId): Response
    {
        $this->gamesSynchronizer->execute($steamUserId);

        return $this->noContentResponse();
    }
}

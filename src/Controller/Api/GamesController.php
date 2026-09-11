<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Services\Api\Synchronization\SteamGameSynchronizerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: "games", name: "api_games_")]
final readonly class GamesController
{
    public function __construct(
        private SteamGameSynchronizerInterface $steamGameSynchronizer
    ) {

    }

    #[Route(path: "", name: "get_games", methods: ["GET"])]
    public function getGames(): Response
    {
        $this->steamGameSynchronizer->synchronize();
    }
}

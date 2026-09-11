<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Services\Api\Synchronization\SteamGameDataPulling\SteamGameDataPuller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: "games", name: "api_games_")]
final readonly class GamesController
{
    public function __construct(
        private readonly SteamGameDataPuller $steamGameDataPuller
    ) {

    }

    #[Route(path: "", name: "get_games", methods: ["GET"])]
    public function getGames(): Response
    {
        dd($this->steamGameDataPuller->pull());
    }
}

<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Services\Api\Synchronization\SteamGamesSynchronizerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: "games", name: "api_games_")]
final readonly class GamesController
{
    public function __construct(
        private SteamGamesSynchronizerInterface $steamGameSynchronizer,
        private ParameterBagInterface $parameterBag,
    ) {

    }

    #[Route(path: "", name: "get_games", methods: ["GET"])]
    public function getGames(): Response
    {
        $steamUserId = (int) $this->parameterBag->get("app.steam_user_id");
        $this->steamGameSynchronizer->execute($steamUserId);
    }
}

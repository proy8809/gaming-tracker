<?php

declare(strict_types=1);

namespace App\Services\Api\Synchronization;

use App\Services\Api\SteamClient\PlayerService\GetOwnedGames\GetOwnedGames;
use App\Services\Api\SteamClient\PlayerService\GetOwnedGames\GetOwnedGamesInput;
use App\Services\Api\SteamClient\PlayerService\GetOwnedGames\GetOwnedGamesItem;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final readonly class SteamGameSynchronizer implements SteamGameSynchronizerInterface
{
    public function __construct(
        private GetOwnedGames $getOwnedGames,
        private ParameterBagInterface $parameterBag,
    ) {
    }

    public function synchronize(): void
    {
        $steamGameData = $this->pullSteamGameData();
        dd($steamGameData);
    }

    private function pullSteamGameData(): array
    {
        $ownedGames = $this->getOwnedGames->execute(
            new GetOwnedGamesInput(
                steamId: $this->parameterBag->get("app.steam_user_id"),
                appIdsFilter: []
            )
        );

        return array_map(fn (GetOwnedGamesItem $ownedGame) => new SteamGameData(
            name: $ownedGame->name,
            steamGameId: $ownedGame->appid,
            steamUserId: (int) $this->parameterBag->get("app.steam_user_id"),
            playtimeForever: $ownedGame->playtimeForever,
            playtimeTwoWeeks: $ownedGame->playtimeTwoWeeks,
            lastPlayed: $ownedGame->rtimeLastPlayed
        ), $ownedGames);
    }
}

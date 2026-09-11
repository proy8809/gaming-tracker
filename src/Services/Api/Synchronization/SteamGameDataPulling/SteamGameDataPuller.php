<?php

declare(strict_types=1);

namespace App\Services\Api\Synchronization\SteamGameDataPulling;

use App\Services\Api\SteamClient\PlayerService\GetOwnedGames\GetOwnedGamesInput;
use App\Services\Api\SteamClient\PlayerService\GetOwnedGames\GetOwnedGamesItem;
use App\Services\Api\SteamClient\PlayerService\GetRecentlyPlayedGames\GetRecentlyPlayedGamesInput;
use App\Services\Api\SteamClient\PlayerService\GetRecentlyPlayedGames\GetRecentlyPlayedGamesItem;
use App\Services\Api\SteamClient\PlayerService\PlayerServiceClientInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class SteamGameDataPuller
{
    public array $steamGameDataList = [];

    public function __construct(
        private readonly PlayerServiceClientInterface $playerServiceClient,
        private readonly ParameterBagInterface $parameterBag,
    ) {
    }

    /**
     * @return SteamGameData[]
     */
    public function pull(): array
    {
        $ownedGames = $this->playerServiceClient->getOwnedGames(
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
            playtimeTwoWeeks: $this->getGamePlaytimeTwoWeeks($ownedGame->appid),
            lastPlayed: $ownedGame->rtimeLastPlayed
        ), $ownedGames);
    }

    /**
     * @param int $steamGameId
     * @return int
     */
    private function getGamePlaytimeTwoWeeks(int $steamGameId): int
    {
        static $recentGames;

        $recentGames ??= $this->playerServiceClient->getRecentlyPlayedGames(
            new GetRecentlyPlayedGamesInput(
                steamId: $this->parameterBag->get("app.steam_user_id"),
            )
        );

        $recentGame = array_find(
            $recentGames,
            static fn(GetRecentlyPlayedGamesItem $item) => $item->appid === $steamGameId
        );

        return $recentGame?->playtimeTwoWeeks ?? 0;
    }
}

<?php

declare(strict_types=1);

namespace App\Services\Api\SteamClient\PlayerService;

use App\Services\Api\SteamClient\PlayerService\GetOwnedGames\GetOwnedGamesInput;
use App\Services\Api\SteamClient\PlayerService\GetOwnedGames\GetOwnedGamesItem;
use App\Services\Api\SteamClient\PlayerService\GetRecentlyPlayedGames\GetRecentlyPlayedGamesInput;
use App\Services\Api\SteamClient\PlayerService\GetRecentlyPlayedGames\GetRecentlyPlayedGamesItem;

interface PlayerServiceClientInterface
{
    /**
     * @param GetOwnedGamesInput $input
     * @return GetOwnedGamesItem[]
     */
    public function getOwnedGames(GetOwnedGamesInput $input): array;


    /**
     * @param GetRecentlyPlayedGamesInput $input
     * @return GetRecentlyPlayedGamesItem[]
     */
    public function getRecentlyPlayedGames(GetRecentlyPlayedGamesInput $input): array;
}

<?php

declare(strict_types=1);

namespace App\Services\Api\SteamClient\PlayerService\GetRecentlyPlayedGames;

final readonly class GetRecentlyPlayedGamesInput
{
    public int $count;
    public string $format;

    /**
     * @param string $steamId
     */
    public function __construct(
        public string $steamId
    ) {
        $this->count = 100;
        $this->format = 'json';
    }
}

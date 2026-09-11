<?php

declare(strict_types=1);

namespace App\Services\Api\SteamClient\PlayerService\GetOwnedGames;

final readonly class GetOwnedGamesInput
{
    public string $format;
    public bool $includeAppInfo;
    public bool $includePlayedFreeGames;

    /**
     * @param string $steamId
     * @param array $appIdsFilter
     */
    public function __construct(
        public string $steamId,
        public array $appIdsFilter = [],
    ) {
        $this->format = 'json';
        $this->includeAppInfo = true;
        $this->includePlayedFreeGames = true;
    }
}

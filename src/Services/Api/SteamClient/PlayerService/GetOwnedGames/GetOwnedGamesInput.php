<?php

declare(strict_types=1);

namespace App\Services\Api\SteamClient\PlayerService\GetOwnedGames;

use App\Services\Api\SteamClient\SteamResponseFormat;

final readonly class GetOwnedGamesInput
{
    public bool $includeAppInfo;
    public bool $includePlayedFreeGames;

    /**
     * @param int $steamId
     * @param SteamResponseFormat $format
     * @param array $appIdsFilter
     */
    public function __construct(
        public int $steamId,
        public SteamResponseFormat $format = SteamResponseFormat::JSON,
        public array $appIdsFilter = [],
    ) {
        $this->includeAppInfo = true;
        $this->includePlayedFreeGames = true;
    }
}

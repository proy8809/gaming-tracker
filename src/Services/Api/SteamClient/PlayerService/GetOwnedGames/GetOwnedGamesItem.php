<?php

declare(strict_types=1);

namespace App\Services\Api\SteamClient\PlayerService\GetOwnedGames;

final readonly class GetOwnedGamesItem
{
    /**
     * @param int $appid
     * @param string $name
     * @param int $playtimeForever
     * @param string $imgIconUrl
     * @param bool $hasCommunityVisibleStats
     * @param int $playtimeWindowsForever
     * @param int $playtimeMacForever
     * @param int $playtimeLinuxForever
     * @param int $playtimeDeckForever
     * @param int $rtimeLastPlayed
     * @param int[] $contentDescriptorIds
     * @param int $playtimeDisconnected
     */
    public function __construct(
        public int $appid,
        public string $name,
        public int $playtimeForever,
        public int $playtimeTwoWeeks,
        public string $imgIconUrl,
        public bool $hasCommunityVisibleStats,
        public int $playtimeWindowsForever,
        public int $playtimeMacForever,
        public int $playtimeLinuxForever,
        public int $playtimeDeckForever,
        public int $rtimeLastPlayed,
        public array $contentDescriptorIds,
        public int $playtimeDisconnected
    ) {
    }
}

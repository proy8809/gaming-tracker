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

    /**
     * @param array $raw
     * @return self
     */
    public static function fromRaw(array $raw): self
    {
        return new self(
            appid: $raw['appid'],
            name: $raw['name'],
            playtimeForever: $raw['playtime_forever'] ?? 0,
            imgIconUrl: $raw['img_icon_url'] ?? '',
            hasCommunityVisibleStats: $raw['has_community_visible_stats'] ?? false,
            playtimeWindowsForever: $raw['playtime_windows_forever'] ?? 0,
            playtimeMacForever: $raw['playtime_mac_forever'] ?? 0,
            playtimeLinuxForever: $raw['playtime_linux_forever'] ?? 0,
            playtimeDeckForever: $raw['playtime_deck_forever'] ?? 0,
            rtimeLastPlayed: $raw['rtime_last_played'] ?? 0,
            contentDescriptorIds: $raw['content_descriptorids'] ?? [],
            playtimeDisconnected: $raw['playtime_disconnected'] ?? 0
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Services\Api\SteamClient\PlayerService\GetRecentlyPlayedGames;

final readonly class GetRecentlyPlayedGamesItem
{
    /**
     * @param int $appid
     * @param string $name
     * @param int $playtimeTwoWeeks
     * @param int $playtimeForever
     * @param string $imgIconUrl
     * @param int $playtimeWindowsForever
     * @param int $playtimeMacForever
     * @param int $playtimeLinuxForever
     * @param int $playtimeDeckForever
     */
    public function __construct(
        public int $appid,
        public string $name,
        public int $playtimeTwoWeeks,
        public int $playtimeForever,
        public string $imgIconUrl,
        public int $playtimeWindowsForever,
        public int $playtimeMacForever,
        public int $playtimeLinuxForever,
        public int $playtimeDeckForever,
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
            playtimeTwoWeeks: $raw['playtime_2weeks'] ?? 0,
            playtimeForever: $raw['playtime_forever'] ?? 0,
            imgIconUrl: $raw['img_icon_url'] ?? '',
            playtimeWindowsForever: $raw['playtime_windows_forever'] ?? 0,
            playtimeMacForever: $raw['playtime_mac_forever'] ?? 0,
            playtimeLinuxForever: $raw['playtime_linux_forever'] ?? 0,
            playtimeDeckForever: $raw['playtime_deck_forever'] ?? 0
        );
    }
}

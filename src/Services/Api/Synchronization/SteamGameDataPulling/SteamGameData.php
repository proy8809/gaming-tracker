<?php

declare(strict_types=1);

namespace App\Services\Api\Synchronization\SteamGameDataPulling;

final readonly class SteamGameData
{
    public function __construct(
        public string $name,
        public int $steamGameId,
        public int $steamUserId,
        public int $playtimeForever,
        public int $playtimeTwoWeeks,
        public int $lastPlayed
    ) {
    }
}

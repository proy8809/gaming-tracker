<?php

declare(strict_types=1);

namespace App\Services\Api\Synchronization;

use App\Entity\Game;

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

    public function toGameEntity(): Game
    {
        $gameImageUrl = sprintf(
            'https://shared.steamstatic.com/store_item_assets/steam/apps/%d/capsule_616x353.jpg',
            $this->steamGameId
        );

        $game = new Game();
        $game->setName($this->name);
        $game->setImageUrl($gameImageUrl);
        $game->setSteamUserId($this->steamUserId);
        $game->setSteamGameId($this->steamGameId);

        return $game;
    }
}

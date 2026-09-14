<?php

declare(strict_types=1);

namespace App\Services\Api\Synchronization\Domain;

final class Game
{
    private const string IMAGE_PATH = 'https://shared.cloudflare.steamstatic.com/store_item_assets/steam/apps/%d/%s.jpg';
    private const string IMAGE_MEDIA = 'capsule_616x353.jpg';

    private ?GameStats $stats = null;

    public function __construct(
        private readonly int $gameId,
        private readonly string $name
    ) {
    }

    public function getGameId(): int
    {
        return $this->gameId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getImageUrl(): string
    {
        return sprintf(self::IMAGE_PATH, $this->gameId, self::IMAGE_MEDIA);
    }


    public function getPlaytimeForever(): int
    {
        return $this->stats->getPlaytimeForever() ?? 0;
    }

    public function getPlaytimeTwoWeeks(): int
    {
        return $this->stats?->getPlaytimeTwoWeeks() ?? 0;
    }

    public function getLastPlayed(): int
    {
        return $this->stats?->getLastPlayed() ?? 0;
    }

    public function setStats(int $playtimeForever, int $playtimeTwoWeeks, int $lastPlayed): void
    {
        $this->stats = new GameStats(
            playtimeForever: $playtimeForever,
            playtimeTwoWeeks: $playtimeTwoWeeks,
            lastPlayed: $lastPlayed
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Services\Api\Synchronization\Domain;

final readonly class GameStats
{
    /**
     * @param int $playtimeForever
     * @param int $playtimeTwoWeeks
     * @param int $lastPlayed
     */
    public function __construct(
        private int $playtimeForever,
        private int $playtimeTwoWeeks,
        private int $lastPlayed,
    ) {
    }

    public function getPlaytimeForever(): int
    {
        return $this->playtimeForever;
    }

    public function getPlaytimeTwoWeeks(): int
    {
        return $this->playtimeTwoWeeks;
    }

    public function getLastPlayed(): int
    {
        return $this->lastPlayed;
    }
}

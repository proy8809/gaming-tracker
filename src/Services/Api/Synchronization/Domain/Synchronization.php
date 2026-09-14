<?php

declare(strict_types=1);

namespace App\Services\Api\Synchronization\Domain;

use RuntimeException;

final class Synchronization
{
    /**
     * @var Game[] $games
     */
    private array $games;

    /**
     * @param int $userId
     */
    public function __construct(
        private readonly int $userId,
    ) {
        $this->games = [];
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getGames(): array
    {
        return $this->games;
    }

    public function exists(int $gameId): bool
    {
        return array_find($this->getGames(), static fn (Game $game) => $game->getGameId() === $gameId) !== null;
    }

    public function addGame(int $gameId, string $name): int
    {
        $this->games[] = new Game(gameId: $gameId, name: $name);

        return array_key_last($this->games);
    }

    /**
     * @param int[] $gameIds
     */
    public function removeGames(array $gameIds): void
    {
        $this->games = array_filter($this->games, static fn (Game $game) => !in_array($game->getGameId(), $gameIds));
    }

    public function setGameStats(int $gameId, int $playtimeForever, int $playtimeTwoWeeks, int $lastPlayed): void
    {
        $key = array_find_key($this->games, static fn (Game $game) => $game->getGameId() === $gameId);

        if (!isset($key)) {
            throw new RuntimeException("Game not found. Stats can't be synchronized.");
        }

        $this->games[$key]->setStats($playtimeForever, $playtimeTwoWeeks, $lastPlayed);
    }
}

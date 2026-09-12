<?php

declare(strict_types=1);

namespace App\Services\Api\SteamClient\PlayerService\GetOwnedGames;

class GetOwnedGamesResponseValidator
{
    public function isValid(array $responseContent): bool
    {
        $responseContentResponseNode = $responseContent['response'] ?? null;
        if (!$responseContentResponseNode) {
            return false;
        }

        $gameCount = $responseContentResponseNode['game_count'] ?? 0;
        if (!$gameCount) {
            return false;
        }

        $games = $responseContentResponseNode['games'] ?? null;
        if (!$games) {
            return false;
        }

        return true;
    }
}

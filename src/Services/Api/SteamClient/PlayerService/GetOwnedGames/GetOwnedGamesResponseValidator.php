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

        if (!isset($responseContentResponseNode['game_count'])) {
            return false;
        }

        if (!isset($responseContentResponseNode['games'])) {
            return false;
        }

        return true;
    }
}

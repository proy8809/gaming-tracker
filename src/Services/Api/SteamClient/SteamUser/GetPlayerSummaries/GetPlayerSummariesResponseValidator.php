<?php

declare(strict_types=1);

namespace App\Services\Api\SteamClient\SteamUser\GetPlayerSummaries;

final readonly class GetPlayerSummariesResponseValidator
{
    public function isValid(array $rawResponse): bool
    {
        if (!isset($rawResponse['response']['players'])) {
            return false;
        }

        if (!is_array($rawResponse['response']['players'])) {
            return false;
        }

        return true;
    }
}

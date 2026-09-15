<?php

declare(strict_types=1);

namespace App\Services\Api\SteamClient\SteamUser\GetPlayerSummaries;

use App\Services\Api\SteamClient\SteamResponseFormat;

final readonly class GetPlayerSummariesInput
{
    public function __construct(
        public array $steamids,
        public SteamResponseFormat $format = SteamResponseFormat::JSON,
    ) {
    }
}

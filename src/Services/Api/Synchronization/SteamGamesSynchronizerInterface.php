<?php

declare(strict_types=1);

namespace App\Services\Api\Synchronization;

interface SteamGamesSynchronizerInterface
{
    public function synchronize(int $steamUserId): void;
}

<?php

declare(strict_types=1);

namespace App\Services\Api\Synchronization;

interface SteamGameSynchronizerInterface
{
    public function synchronize(): void;
}

<?php

declare(strict_types=1);

namespace App\Services\Api\User;

use App\Services\Api\User\Domain\Player;

interface SteamUserExtractorInterface
{
    public function execute(int $steamUserId): Player;

}

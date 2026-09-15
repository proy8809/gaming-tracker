<?php

declare(strict_types=1);

namespace App\Services\Api\User\Domain;

enum PlayerState: int
{
    case Offline = 0;
    case Online = 1;
    case Busy = 2;
    case Away = 3;
    case Snooze = 4;
    case LookingToTrade = 5;
    case LookingToPlay = 6;

    public function toString(): string
    {
        return match($this) {
            self::Offline => 'offline',
            self::Online => 'online',
            self::Busy => 'busy',
            self::Away => 'away',
            self::Snooze => 'snooze',
            self::LookingToTrade => 'looking_to_trade',
            self::LookingToPlay => 'looking_to_play',
        };
    }
}

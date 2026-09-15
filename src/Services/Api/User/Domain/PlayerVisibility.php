<?php

declare(strict_types=1);

namespace App\Services\Api\User\Domain;

enum PlayerVisibility: int
{
    case Private = 1;
    case Public = 3;

    public function toString(): string
    {
        return match($this) {
            self::Private => 'private',
            self::Public => 'public',
        };
    }
}

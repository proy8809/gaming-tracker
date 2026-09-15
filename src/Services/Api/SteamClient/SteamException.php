<?php

declare(strict_types=1);

namespace App\Services\Api\SteamClient;

use Exception;
use Throwable;

class SteamException extends Exception
{
    public static function fromThrowable(Throwable $throwable): self
    {
        return new self(
            message: SteamExceptionType::messageFor($throwable->getCode()),
            code: $throwable->getCode(),
            previous: $throwable
        );
    }

    public static function fromType(SteamExceptionType $type): self
    {
        return new self(
            message: SteamExceptionType::messageFor($type->value),
            code: $type->value,
            previous: null
        );
    }
}

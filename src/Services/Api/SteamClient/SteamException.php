<?php

declare(strict_types=1);

namespace App\Services\Api\SteamClient;

use Exception;
use Throwable;

class SteamException extends Exception
{
    /**
     * @param Throwable $throwable
     */
    public function __construct(Throwable $throwable)
    {
        parent::__construct(SteamExceptionType::messageFor($throwable->getCode()), $throwable->getCode(), $throwable);
    }
}

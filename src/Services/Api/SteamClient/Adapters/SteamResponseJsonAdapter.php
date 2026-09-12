<?php

declare(strict_types=1);

namespace App\Services\Api\SteamClient\Adapters;

use App\Services\Api\SteamClient\SteamResponseFormat;
use Symfony\Contracts\HttpClient\ResponseInterface;

final readonly class SteamResponseJsonAdapter implements SteamResponseAdapter
{
    public function supports(): SteamResponseFormat
    {
        return SteamResponseFormat::JSON;
    }

    public function parse(ResponseInterface $response): array
    {
        return $response->toArray();
    }
}

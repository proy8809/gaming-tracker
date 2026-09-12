<?php

declare(strict_types=1);

namespace App\Services\Api\SteamClient\Adapters;

use App\Services\Api\SteamClient\SteamResponseFormat;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Contracts\HttpClient\ResponseInterface;

#[AutoconfigureTag(SteamResponseAdapter::class)]
interface SteamResponseAdapter
{
    /**
     * @return SteamResponseFormat
     */
    public function supports(): SteamResponseFormat;

    /**
     * @param ResponseInterface $response
     * @return mixed[]
     */
    public function parse(ResponseInterface $response): array;
}

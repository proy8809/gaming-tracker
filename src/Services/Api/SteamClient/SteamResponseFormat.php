<?php

declare(strict_types=1);

namespace App\Services\Api\SteamClient;

enum SteamResponseFormat: string
{
    case JSON = 'json';
    case XML = 'xml';
    case VDF = 'vdf';
}

<?php

declare(strict_types=1);

namespace App\Services\Api\SteamClient\Adapters;

use App\Services\Api\SteamClient\SteamResponseFormat;
use RuntimeException;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

final readonly class SteamResponseAdapterFactory
{
    public function __construct(
        #[AutowireIterator(SteamResponseAdapter::class)]
        private iterable $responseAdapters
    ) {
    }

    public function forSteamResponseFormat(SteamResponseFormat $responseFormat): SteamResponseAdapter
    {
        $adapter = array_find(
            iterator_to_array($this->responseAdapters),
            static fn (SteamResponseAdapter $adapter) => $adapter->supports() === $responseFormat
        );

        if (!$adapter) {
            throw new RuntimeException(sprintf('Unsupported response format: %s', $responseFormat->value));
        }

        return $adapter;
    }
}

<?php

declare(strict_types=1);

namespace App\Services\Api\SteamClient\SteamUser\GetPlayerSummaries;

use App\Services\Api\SteamClient\Adapters\SteamResponseAdapterFactory;
use App\Services\Api\SteamClient\SteamException;
use App\Services\Api\SteamClient\SteamExceptionType;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

final readonly class GetPlayerSummaries
{
    private const string PATH = 'ISteamUser/GetPlayerSummaries/v0002';

    public function __construct(
        private HttpClientInterface $httpClient,
        private SteamResponseAdapterFactory $responseAdapterFactory,
        private GetPlayerSummariesResponseValidator $responseValidator,
        private ParameterBagInterface $parameterBag,
    ) {
    }


    /**
     * @return GetPlayerSummariesResponseItem[]
     * @throws SteamException
     */
    public function execute(GetPlayerSummariesInput $input): array
    {
        $path = implode('/', [$this->parameterBag->get('app.steam_base_uri'), self::PATH]) . '/';

        try {
            $response = $this->httpClient->request('GET', $path, [
                'query' => [
                    'key' => $this->parameterBag->get('app.steam_api_key'),
                    'steamids' => implode(',', $input->steamids),
                    'format' => $input->format->value,
                ],
            ]);

            $adapter = $this->responseAdapterFactory->forSteamResponseFormat($input->format);
            $rawResponse = $adapter->parse($response);
        } catch (Throwable $t) {
            throw SteamException::fromThrowable($t);
        }

        if (!$this->responseValidator->isValid($rawResponse)) {
            throw SteamException::fromType(SteamExceptionType::InternalServerError);
        }

        return array_map(GetPlayerSummariesResponseItem::fromRaw(...), $rawResponse['response']['players'] ?? []);
    }
}

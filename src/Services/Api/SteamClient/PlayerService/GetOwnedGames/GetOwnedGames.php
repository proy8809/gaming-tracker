<?php

declare(strict_types=1);

namespace App\Services\Api\SteamClient\PlayerService\GetOwnedGames;

use App\Services\Api\SteamClient\Adapters\SteamResponseAdapterFactory;
use App\Services\Api\SteamClient\SteamException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

final readonly class GetOwnedGames
{
    private const string PATH = 'IPlayerService/GetOwnedGames/v0001';

    public function __construct(
        private HttpClientInterface            $httpClient,
        private SteamResponseAdapterFactory    $responseAdapterFactory,
        private GetOwnedGamesResponseValidator $responseValidator,
        private ParameterBagInterface          $parameterBag,
    ) {

    }

    /**
     * @param GetOwnedGamesInput $input
     * @return GetOwnedGamesResponseItem[]
     * @throws SteamException
     */
    public function execute(GetOwnedGamesInput $input): array
    {
        $path = implode('/', [$this->parameterBag->get('app.steam_base_uri'), self::PATH]) . '/';

        try {
            $response = $this->httpClient->request('GET', $path, [
                'query' => [
                    'key' => $this->parameterBag->get('app.steam_api_key'),
                    'format' => $input->format,
                    'input_json' => json_encode([
                        'steamid' => $input->steamId,
                        'include_appinfo' => $input->includeAppInfo,
                        'include_played_free_games' => $input->includePlayedFreeGames,
                        'appids_filter' => $input->appIdsFilter,
                    ], JSON_THROW_ON_ERROR),
                ],
            ]);

            $adapter = $this->responseAdapterFactory->forSteamResponseFormat($input->format);
            $rawResponse = $adapter->parse($response);

            if (!$this->responseValidator->isValid($rawResponse)) {
                return [];
            }

            return array_map(GetOwnedGamesResponseItem::fromRaw(...), $rawResponse['response']['games'] ?? []);
        } catch (Throwable $t) {
            throw new SteamException($t);
        }
    }
}

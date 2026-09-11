<?php

declare(strict_types=1);

namespace App\Services\Api\SteamClient\PlayerService;

use App\Services\Api\SteamClient\PlayerService\GetOwnedGames\GetOwnedGamesInput;
use App\Services\Api\SteamClient\PlayerService\GetOwnedGames\GetOwnedGamesItem;
use App\Services\Api\SteamClient\PlayerService\GetRecentlyPlayedGames\GetRecentlyPlayedGamesInput;
use App\Services\Api\SteamClient\PlayerService\GetRecentlyPlayedGames\GetRecentlyPlayedGamesItem;
use App\Services\Api\SteamClient\SteamException;
use InvalidArgumentException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

final readonly class PlayerServiceClient implements PlayerServiceClientInterface
{
    private const string PATH = 'IPlayerService';

    /**
     * @param HttpClientInterface $httpClient
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(
        private HttpClientInterface $httpClient,
        private ParameterBagInterface $parameterBag,
    ) {
    }

    /**
     * @param GetOwnedGamesInput $input
     * @return GetOwnedGamesItem[]
     * @throws SteamException
     */
    public function getOwnedGames(GetOwnedGamesInput $input): array
    {
        $path = $this->getPathTo('GetOwnedGames/v0001');

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

            if ($response->toArray()['response']['game_count'] === 0) {
                return [];
            }

            return array_map(
                static fn (array $raw) => GetOwnedGamesItem::fromRaw($raw),
                $response->toArray()['response']['games']
            );
        } catch (Throwable $t) {
            throw new SteamException($t);
        }
    }

    /**
     * @param GetRecentlyPlayedGamesInput $input
     * @return GetRecentlyPlayedGamesItem[]
     * @throws SteamException
     */
    public function getRecentlyPlayedGames(GetRecentlyPlayedGamesInput $input): array
    {
        $path = $this->getPathTo('GetRecentlyPlayedGames/v0001');

        try {
            $response = $this->httpClient->request('GET', $path, [
                'query' => [
                    'key' => $this->parameterBag->get('app.steam_api_key'),
                    'steamid' => $input->steamId,
                    'count' => $input->count,
                    'format' => $input->format,
                ],
            ]);

            if ($response->toArray()['response']['total_count'] === 0) {
                return [];
            }

            return array_map(
                static fn (array $raw) => GetRecentlyPlayedGamesItem::fromRaw($raw),
                $response->toArray()['response']['games']
            );
        } catch (Throwable $t) {
            throw new SteamException($t);
        }
    }

    /**
     * @param string $endpoint
     * @return string
     */
    private function getPathTo(string $endpoint): string
    {
        if (empty(trim($endpoint))) {
            throw new InvalidArgumentException("The endpoint has to be declared.");
        }

        return implode('/', [$this->parameterBag->get('app.steam_base_uri'), self::PATH, $endpoint]) . '/';
    }
}

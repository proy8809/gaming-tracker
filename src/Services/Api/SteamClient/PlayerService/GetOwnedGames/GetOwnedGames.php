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
            $parsedResponse = $adapter->parse($response);

            if (!$this->responseValidator->isValid($parsedResponse)) {
                return [];
            }

            return array_map($this->rawToGetOwnedGamesItem(...), $parsedResponse['response']['games'] ?? []);
        } catch (Throwable $t) {
            throw new SteamException($t);
        }
    }

    /**
     * @param mixed[] $raw
     * @return GetOwnedGamesResponseItem
     */
    private function rawToGetOwnedGamesItem(array $raw): GetOwnedGamesResponseItem
    {
        return new GetOwnedGamesResponseItem(
            appid: $raw['appid'],
            name: $raw['name'],
            playtimeForever: $raw['playtime_forever'] ?? 0,
            playtimeTwoWeeks: $raw['playtime_2weeks'] ?? 0,
            imgIconUrl: $raw['img_icon_url'] ?? '',
            hasCommunityVisibleStats: $raw['has_community_visible_stats'] ?? false,
            playtimeWindowsForever: $raw['playtime_windows_forever'] ?? 0,
            playtimeMacForever: $raw['playtime_mac_forever'] ?? 0,
            playtimeLinuxForever: $raw['playtime_linux_forever'] ?? 0,
            playtimeDeckForever: $raw['playtime_deck_forever'] ?? 0,
            rtimeLastPlayed: $raw['rtime_last_played'] ?? 0,
            contentDescriptorIds: $raw['content_descriptorids'] ?? [],
            playtimeDisconnected: $raw['playtime_disconnected'] ?? 0
        );
    }
}

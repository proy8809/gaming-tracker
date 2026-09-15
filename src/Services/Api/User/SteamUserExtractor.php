<?php

declare(strict_types=1);

namespace App\Services\Api\User;

use App\Services\Api\DomainException;
use App\Services\Api\SteamClient\SteamException;
use App\Services\Api\SteamClient\SteamResponseFormat;
use App\Services\Api\SteamClient\SteamUser\GetPlayerSummaries\GetPlayerSummaries;
use App\Services\Api\SteamClient\SteamUser\GetPlayerSummaries\GetPlayerSummariesInput;
use App\Services\Api\User\Domain\Player;
use App\Services\Api\User\Domain\PlayerState;
use App\Services\Api\User\Domain\PlayerVisibility;

final readonly class SteamUserExtractor implements SteamUserExtractorInterface
{
    public function __construct(
        private GetPlayerSummaries $getPlayerSummaries,
    ) {
    }

    /**
     * @throws DomainException
     * @throws SteamException
     */
    public function execute(int $steamUserId): Player
    {
        $players = $this->getPlayerSummaries->execute(
            new GetPlayerSummariesInput(
                steamids: [$steamUserId],
                format: SteamResponseFormat::JSON
            )
        );

        if (count($players) === 0) {
            throw new DomainException("No player recovered from steam.");
        }

        if (count($players) > 1) {
            throw new DomainException("More than one player recovered from steam.");
        }

        return new Player(
            userId: $players[0]->steamid,
            profileName: $players[0]->personaname,
            profileUrl: $players[0]->profileurl,
            avatar: $players[0]->avatarfull,
            playerState: PlayerState::from($players[0]->personastate),
            playerVisibility: PlayerVisibility::from($players[0]->communityvisibilitystate)
        );
    }
}

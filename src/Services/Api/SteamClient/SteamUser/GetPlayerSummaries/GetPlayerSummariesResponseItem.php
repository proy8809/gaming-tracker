<?php

declare(strict_types=1);

namespace App\Services\Api\SteamClient\SteamUser\GetPlayerSummaries;

final readonly class GetPlayerSummariesResponseItem
{
    private function __construct(
        public int $steamid,
        public string $personaname,
        public string $profileurl,
        public string $avatar,
        public string $avatarfull,
        public int $personastate,
        public int $communityvisibilitystate,
        public ?int $profilestate,
        public int $lastlogoff,
        public ?int $commentpermission,
    ) {
    }

    public static function fromRaw(array $data): self
    {
        return new self(
            steamid: (int) $data['steamid'],
            personaname: $data['personaname'],
            profileurl: $data['profileurl'],
            avatar: $data['avatar'],
            avatarfull: $data['avatarfull'],
            personastate: (int) $data['personastate'],
            communityvisibilitystate: (int) $data['communityvisibilitystate'],
            profilestate: (int) ($data['profilestate'] ?? null),
            lastlogoff: (int) $data['lastlogoff'],
            commentpermission: (int) ($data['commentpermission'] ?? null),
        );
    }
}

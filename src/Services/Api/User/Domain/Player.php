<?php

declare(strict_types=1);

namespace App\Services\Api\User\Domain;

final readonly class Player
{
    public function __construct(
        public int $userId,
        public string $profileName,
        public string $profileUrl,
        public string $avatar,
        public PlayerState $playerState,
        public PlayerVisibility $playerVisibility
    ) {
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'profile_name' => $this->profileName,
            'profile_url' => $this->profileUrl,
            'avatar' => $this->avatar,
            'player_state' => $this->playerState->toString(),
            'player_visibility' => $this->playerVisibility->toString()
        ];
    }
}

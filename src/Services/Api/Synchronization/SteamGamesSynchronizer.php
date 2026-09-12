<?php

declare(strict_types=1);

namespace App\Services\Api\Synchronization;

use App\Entity\Game;
use App\Repository\GameRepository;
use App\Services\Api\SteamClient\PlayerService\GetOwnedGames\GetOwnedGames;
use App\Services\Api\SteamClient\PlayerService\GetOwnedGames\GetOwnedGamesInput;
use App\Services\Api\SteamClient\PlayerService\GetOwnedGames\GetOwnedGamesResponseItem;
use App\Services\Api\SteamClient\SteamException;
use Doctrine\ORM\EntityManagerInterface;

final readonly class SteamGamesSynchronizer implements SteamGamesSynchronizerInterface
{
    public function __construct(
        private GetOwnedGames $getOwnedGames,
        private GameRepository $gameRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @param int $steamUserId
     * @throws SteamException
     */
    public function synchronize(int $steamUserId): void
    {
        $steamGamesData = $this->getSteamGamesData($steamUserId);
        $storedGames = $this->gameRepository->findBySteamUserId($steamUserId);

        dd($storedGames);
        $addedSteamGameIds = $this->getAddedSteamGameIds($steamGamesData, $storedGames);
        $removedSteamGameIds = $this->getRemovedSteamGameIds($steamGamesData, $storedGames);

        $addedGamesData = array_filter(
            $steamGamesData,
            static fn(SteamGameData $steamGameData) => in_array($steamGameData->steamGameId, $addedSteamGameIds)
        );

        foreach ($addedGamesData as $addedGameData) {
            $this->entityManager->persist($addedGameData->toGameEntity());
        }

        $removedGames = array_filter(
            $storedGames,
            static fn(Game $game) => in_array($game->getSteamGameId(), $removedSteamGameIds)
        );

        foreach ($removedGames as $removedGame) {
            $this->entityManager->remove($removedGame);
        }

        $this->entityManager->flush();
    }

    /**
     * @param int $steamUserId
     * @return SteamGameData[]
     * @throws SteamException
     */
    private function getSteamGamesData(int $steamUserId): array
    {
        $ownedGames = $this->getOwnedGames->execute(
            new GetOwnedGamesInput(
                steamId: $steamUserId,
                appIdsFilter: []
            )
        );

        return array_map(fn (GetOwnedGamesResponseItem $ownedGame) => new SteamGameData(
            name: $ownedGame->name,
            steamGameId: $ownedGame->appid,
            steamUserId: $steamUserId,
            playtimeForever: $ownedGame->playtimeForever,
            playtimeTwoWeeks: $ownedGame->playtimeTwoWeeks,
            lastPlayed: $ownedGame->rtimeLastPlayed
        ), $ownedGames);
    }

    /**
     * @param SteamGameData[] $steamGamesData
     * @param Game[] $storedGamesData
     * @return int[]
     */
    private function getAddedSteamGameIds(array $steamGamesData, array $storedGamesData): array
    {
        $storedGameIds = array_map(static fn (Game $game) => $game->getSteamGameId(), $storedGamesData);
        $steamGameIds = array_map(static fn (SteamGameData $gameData) => $gameData->steamGameId, $steamGamesData);

        return array_diff($steamGameIds, $storedGameIds);
    }

    /**
     * @param SteamGameData[] $steamGamesData
     * @param Game[] $storedGamesData
     * @return int[]
 */
    private function getRemovedSteamGameIds(array $steamGamesData, array $storedGamesData): array
    {
        $storedGameIds = array_map(static fn (Game $game) => $game->getSteamGameId(), $storedGamesData);
        $steamGameIds = array_map(static fn (SteamGameData $gameData) => $gameData->steamGameId, $steamGamesData);

        return array_diff($storedGameIds, $steamGameIds);
    }
}

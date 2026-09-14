<?php

declare(strict_types=1);

namespace App\Services\Api\Synchronization;

use App\Entity\Game as GameEntity;
use App\Entity\Synchronization as SynchronizationEntity;
use App\Entity\SynchronizationGame as SynchronizationGameEntity;
use App\Repository\GameRepository;
use App\Services\Api\SteamClient\PlayerService\GetOwnedGames\GetOwnedGames;
use App\Services\Api\SteamClient\PlayerService\GetOwnedGames\GetOwnedGamesInput;
use App\Services\Api\SteamClient\PlayerService\GetOwnedGames\GetOwnedGamesResponseItem;
use App\Services\Api\SteamClient\SteamResponseFormat;
use App\Services\Api\Synchronization\Domain\Synchronization;
use Doctrine\ORM\EntityManagerInterface;

final readonly class SteamGamesSynchronizer implements SteamGamesSynchronizerInterface
{
    public function __construct(
        private GetOwnedGames $getOwnedGames,
        private EntityManagerInterface $entityManager,
        private GameRepository $gameRepository
    ) {
    }


    public function execute(int $steamUserId): void
    {
        $synchronization = new Synchronization($steamUserId);

        $gameEntities = $this->gameRepository->findBySteamUserId($steamUserId);
        $steamGames = $this->getOwnedGames->execute(
            new GetOwnedGamesInput(
                steamId: $steamUserId,
                format: SteamResponseFormat::JSON,
                appIdsFilter: []
            )
        );

        $this->getDataFromSteam($synchronization, $gameEntities, $steamGames);
        $this->saveActiveGames($synchronization, $gameEntities);
        $this->removeObsoleteGames($synchronization, $gameEntities);
        $this->entityManager->flush();
    }

    /**
     * @param GameEntity[] $gameEntities
     * @param GetOwnedGamesResponseItem[] $steamGames
     */
    private function getDataFromSteam(Synchronization $synchronization, array $gameEntities, array $steamGames): void
    {
        foreach ($gameEntities as $gameEntity) {
            $synchronization->addGame($gameEntity->getSteamGameId(), $gameEntity->getName());
        }

        $gameEntitySteamGameIds = array_map(static fn (GameEntity $gameEntity) => $gameEntity->getSteamGameId(), $gameEntities);
        $steamGameIds = array_map(static fn (GetOwnedGamesResponseItem $steamGame) => $steamGame->appid, $steamGames);
        $removedSteamGameIds = array_diff($gameEntitySteamGameIds, $steamGameIds);

        $synchronization->removeGames($removedSteamGameIds);

        foreach ($steamGames as $steamGame) {
            if (!$synchronization->exists($steamGame->appid)) {
                $synchronization->addGame($steamGame->appid, $steamGame->name);
            }

            $synchronization->setGameStats(
                $steamGame->appid,
                $steamGame->playtimeForever,
                $steamGame->playtimeTwoWeeks,
                $steamGame->rtimeLastPlayed
            );
        }
    }

    /**
     * @param GameEntity[] $gameEntities
     */
    private function saveActiveGames(Synchronization $synchronization, array $gameEntities): void
    {
        $synchronizationEntity = new SynchronizationEntity();
        $synchronizationEntity->setSteamUserId($synchronization->getUserId());
        $this->entityManager->persist($synchronizationEntity);

        foreach ($synchronization->getGames() as $game) {
            $gameEntity = array_find(
                $gameEntities,
                static fn (GameEntity $storedGame) => $storedGame->getSteamGameId() === $game->getGameId()
            );

            if (!$gameEntity) {
                $gameEntity = new GameEntity();
                $gameEntity->setName($game->getName());
                $gameEntity->setImageUrl($game->getImageUrl());
                $gameEntity->setSteamGameId($game->getGameId());
                $gameEntity->setSteamUserId($synchronization->getUserId());
                $this->entityManager->persist($gameEntity);
            }

            $synchronizationGameEntity = new SynchronizationGameEntity();
            $synchronizationGameEntity->setPlaytimeForever($game->getPlaytimeForever());
            $synchronizationGameEntity->setPlaytimeTwoWeeks($game->getPlaytimeTwoWeeks());
            $synchronizationGameEntity->setLastPlayed(\DateTimeImmutable::createFromTimestamp($game->getLastPlayed()));
            $synchronizationGameEntity->setGame($gameEntity);
            $synchronizationGameEntity->setSynchronization($synchronizationEntity);

            $this->entityManager->persist($synchronizationGameEntity);
        }
    }

    private function removeObsoleteGames(Synchronization $synchronization, array $gameEntities): void
    {
        $activeSteamGameIds = array_map(static fn ($game) => $game->getGameId(), $synchronization->getGames());
        $obsoleteGameEntities = array_filter(
            $gameEntities,
            static fn (GameEntity $gameEntity) => !in_array($gameEntity->getSteamGameId(), $activeSteamGameIds)
        );

        foreach ($obsoleteGameEntities as $obsoleteGameEntity) {
            $this->entityManager->remove($obsoleteGameEntity);
        }
    }
}

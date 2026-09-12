<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Game>
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    /**
     * @param int $steamUserId
     * @return Game[]
     */
    public function findBySteamUserId(int $steamUserId): array
    {
        return $this->findBy(['steamUserId' => $steamUserId]);
    }

    /**
     * @param int $steamUserId
     * @param array $steamGameIds
     * @return void
     */
    public function deleteBySteamUserIdAndSteamGameIds(int $steamUserId, array $steamGameIds): void
    {
        $qb = $this->createQueryBuilder('g');
        $qb->delete()
            ->where($qb->expr()->eq('g.steamUserId', ':steamUserId'))
            ->andWhere($qb->expr()->in('g.steamGameId', ':steamGameIds'))
            ->setParameter('steamGameIds', $steamGameIds)
            ->getQuery()
            ->execute();
    }
}

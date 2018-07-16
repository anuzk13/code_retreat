<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Game::class);
    }

   /**
    * @return Game|null
    */
    public function getAvailableGame()
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.playerTwo IS NOT NULL')
            ->andWhere('g.playerTwo IS NULL')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getActiveGame(Player $player)
    {
        return $this->createQueryBuilder('g')
            ->where('g.playerOne = :player_id AND g.active = TRUE')
            ->orWhere('g.playerTwo = :player_id AND g.active = TRUE')
            ->setParameter('player_id',  $player->getId())
            ->getQuery()
            ->getOneOrNullResult();
    }
}

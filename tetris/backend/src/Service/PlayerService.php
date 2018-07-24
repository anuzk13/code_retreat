<?php

namespace App\Service;

use App\Entity\Player;

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class PlayerService
{
    private $entityManager;
    private $key;

    public function __construct(EntityManagerInterface $entityManager, $secret)
    {
        $this->entityManager = $entityManager;
        $this->key = Key::loadFromAsciiSafeString($secret);
    }

   /**
    * @return Player
    */
    public function registerPlayer($name) {
        try {
            $player = new Player();
            $player->setName($name);
            $this->entityManager->persist($player);
            $this->entityManager->flush();
            return $player;
        }
        catch (UniqueConstraintViolationException $e) {
            $pRep = $this->entityManager->getRepository(Player::class);
            $player = $pRep->findOneBy(['name' => $name]);
            return $player;
        }
    }

    /**
    * @return string Ciphertext string representing $player_id encrypted with the service key
    */
    public function playerToken(Player $player) {
        return Crypto::encrypt((string)$player->getId(), $this->key);
    }

    /**
    * @return Player
    */
    public function playerFromToken($token) {
        $pId = (int)Crypto::decrypt($token, $this->key);
        $pRep = $this->entityManager->getRepository(Player::class);
        return $pRep->find($pId);
    }

}
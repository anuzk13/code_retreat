<?php

namespace App\Service;

use App\Entity\Player;
use App\Entity\Game;
use App\Entity\Board;
use App\Exception\PlayerAlreadyInGameException;

use Doctrine\ORM\EntityManagerInterface;

class GameService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

   /**
    * @return Game
    */
    public function registerGame(Player $player) {
        $gRep = $this->entityManager->getRepository(Game::class);
        $game = $gRep->getActiveGame($player);
        if ($game) {
            throw new PlayerAlreadyInGameException('The player has a game already active');
        } else {
            $this->entityManager->transactional(function($em) use ($player, $gRep, &$game) {
                if ($game = $gRep->getAvailableGame()) {
                    $game->setPlayerTwo($player);
                } else {
                    $board = new Board();
                    $board->setPositions([]);
                    $game = new Game();
                    $game->setActive(true);
                    $game->setActivePlayer(Game::ACTIVE_PLAYER_ONE);
                    $game->setPlayerOne($player);
                    $game->setBoard($board);
                    $em->persist($board);
                }
                $em->persist($game);
                $em->flush();
            });
            return $game;
        }
    }

    public function isCurrentPlayer(Player $player, Game $game) {
        $playerOneTurn = $game->getActivePlayer() === Game::ACTIVE_PLAYER_ONE;
        if ($playerOneTurn && $player->getId() === $game->getPlayerOne()->getId()) {
            return true;
        }  else if (!$playerOneTurn && $game->getPlayerTwo() && $player->getId() === $game->getPlayerTwo()->getId()) {
            return true;
        }
        return false;
    }
}
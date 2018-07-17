<?php

namespace App\Service;

use App\Entity\Player;
use App\Entity\Game;
use App\Entity\Board;
use App\Exception\PlayerAlreadyInGameException;
use App\Exception\PlayerTwoSymbolSelection;
use App\View\GameStatus;

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

    private function isCurrentPlayer(Player $player, Game $game) {
        $playerOneTurn = $game->getActivePlayer() === Game::ACTIVE_PLAYER_ONE;
        if ($playerOneTurn && $player->getId() === $game->getPlayerOne()->getId()) {
            return true;
        }  else if (!$playerOneTurn && $game->getPlayerTwo() && $player->getId() === $game->getPlayerTwo()->getId()) {
            return true;
        }
        return false;
    }

    private function getPlayerSymbol (Player $player, Game $game) {
        if ($player->getId() === $game->getPlayerOne()->getId()) {
            return $game->getPOneSymbol();
        } else {
            return $game->getPTwoSymbol();
        }
    }

    public function getAvailableSymbols(Game $game) {
        if (!$game->getPOneSymbol()) {
            return array(Board::CROSS, Board::NOUGHT);
        } else {
            throw new PlayerTwoSymbolSelection('Only the player one can select a symbol');
        }
    }

    public function getGameStatus (Player $player, Game $game)
    {
        $icp = $this->isCurrentPlayer($player, $game);
        $ps = $this->getPlayerSymbol($player, $game);
        return new GameStatus($game, $icp, $ps);
    }

    public function getPlayerTwoSymbol($pOneSymbol) {
        return $pOneSymbol === Board::NOUGHT ? Board::CROSS : Board::NOUGHT;
    }
}
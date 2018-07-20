<?php

namespace App\Service;

use App\Entity\Player;
use App\Entity\Game;
use App\Entity\Board;
use App\Exception\PlayerAlreadyInGameException;
use App\Exception\PlayerTwoSymbolSelection;
use App\Exception\InvalidPositionExecption;
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
                $games = $gRep->getAvailableGames();
                $game = count($games) ? $games[0] : null;
                if ($game) {
                    $game->setPlayerTwo($player);
                } else {
                    $board = new Board();
                    $positions = array_fill(0, 9, null);
                    $board->setPositions($positions);
                    $game = new Game();
                    $game->setActive(true);
                    $game->setActivePlayer(Game::ACTIVE_PLAYER_ONE);
                    $game->setPlayerOne($player);
                    $game->setBoard($board);
                    $game->setMoveCount(0);
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

    private function gameVictory(Board $board) {
        $positions = $board->getPositions();
        // Check that rows match
        for ($i=0; $i < 3; $i++) { 
            if ($positions[$i*3] && $positions[$i*3] === $positions[$i*3+1] && $positions[$i*3+1] === $positions[$i*3+2]) {
                return [$i*3, $i*3+1, $i*3+2];
            }
        }
        // Check that columns match
        for($i = 0; $i < 3; $i++){
            if ($positions[$i] && $positions[$i] === $positions[$i+3] && $positions[$i+3] === $positions[$i+6]) {
                return [$i, $i+3, $i+6];
            }
        }
    
        //check diagonals 
        if ($positions[0] && $positions[0] === $positions[4] && $positions[4] === $positions[8]) {
            return [0,4,8];
        }
        if ($positions[2] && $positions[2] === $positions[4] && $positions[4] === $positions[6]) {
            return [2,4,6];
        }

        return [];
    }

    private function gameEnded (Game $game) {
        return $game->getMoveCount() === 9;
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
        $ge = $this->gameEnded($game);
        $gameVictory = $this->gameVictory($game->getBoard());
        $iw = ($game->getWinner() && $game->getWinner()->getId() === $player->getId()) || (count($gameVictory) && !$game->getWinner());
        $il = $game->getWinner() ? $game->getWinner()->getId() !== $player->getId() : false;
        $id = $ge && !$iw;
        return new GameStatus($game, $icp, $ps, $il, $iw, $id, $gameVictory);
    }

    public function playPiece (Player $player, Game $game, $position)
    {
        $positions = $game->getBoard()->getPositions();
        if ($positions[$position]) {
            throw new InvalidPositionExecption('there is already a piece in this cell');
        } else {
            $symbol = $this->getPlayerSymbol($player, $game);
            $positions[$position] = $symbol;
            $game->getBoard()->setPositions($positions);
            $active_player = $game->getActivePlayer() === Game::ACTIVE_PLAYER_ONE ? Game::ACTIVE_PLAYER_TWO : Game::ACTIVE_PLAYER_ONE;
            $game->setActivePlayer($active_player);
            $moveCount = $game->getMoveCount();
            $game->setMoveCount($moveCount + 1);
            $gameStatus = $this->getGameStatus($player, $game);
            if ($gameStatus->getIsWinner()) {
                $game->setWinner($player);
                $game->setActive(false);
            } 
            if ($gameStatus->getIsDraw()) {
                $game->setActive(false);
            }
            $this->entityManager->flush();
            return $gameStatus;
        }

    }

    public function getPlayerTwoSymbol($pOneSymbol) {
        return $pOneSymbol === Board::NOUGHT ? Board::CROSS : Board::NOUGHT;
    }
}
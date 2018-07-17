<?php

namespace App\View;

use App\Entity\Game;
use App\Entity\Board;
use App\Entity\Player;

class GameStatus
{

    function __construct(Game $game, $isCurrentPlayer, $playerSymbol) {
        $this->gameId = $game->getId();
        $this->isCurrentPlayer = $isCurrentPlayer;
        $this->board = $game->getBoard();
        $this->active = $game->getActive();
        $this->playerSymbol = $playerSymbol;
        $this->playerTwo = $game->getPlayerTwo();
    }

    private $gameId;

    private $board;

    private $active;

    private $isCurrentPlayer;

    private $playerSymbol;

    private $playerTwo;

    public function getGameId()
    {
        return $this->gameId;
    }

    public function getBoard(): ?Board
    {
        return $this->board;
    }

    public function getPlayerSymbol(): ?string
    {
        return $this->playerSymbol;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function getIsCurrentPlayer(): ?bool
    {
        return $this->isCurrentPlayer;
    }

    public function getPlayerTwo(): ?Player
    {
        return $this->playerTwo;
    }

}

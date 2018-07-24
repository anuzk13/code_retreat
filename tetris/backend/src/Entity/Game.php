<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GameRepository")
 */
class Game
{
    CONST ACTIVE_PLAYER_ONE = 1;
    CONST ACTIVE_PLAYER_TWO = 2;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Player")
     * @ORM\JoinColumn(nullable=false)
     */
    private $playerOne;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Player")
     */
    private $playerTwo;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Board", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $board;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pOneSymbol;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pTwoSymbol;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="integer")
     */
    private $activePlayer;

    private $isCurrentPlayer;

    /**
     * @ORM\Column(type="integer")
     */
    private $moveCount;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Player")
     */
    private $winner;

    public function getId()
    {
        return $this->id;
    }

    public function getPlayerOne(): ?Player
    {
        return $this->playerOne;
    }

    public function setPlayerOne(?Player $playerOne): self
    {
        $this->playerOne = $playerOne;

        return $this;
    }

    public function getPlayerTwo(): ?Player
    {
        return $this->playerTwo;
    }

    public function setPlayerTwo(?Player $playerTwo): self
    {
        $this->playerTwo = $playerTwo;

        return $this;
    }

    public function getBoard(): ?Board
    {
        return $this->board;
    }

    public function setBoard(Board $board): self
    {
        $this->board = $board;

        return $this;
    }

    public function getPOneSymbol(): ?string
    {
        return $this->pOneSymbol;
    }

    public function setPOneSymbol(?string $pOneSymbol): self
    {
        $this->pOneSymbol = $pOneSymbol;

        return $this;
    }

    public function getPTwoSymbol(): ?string
    {
        return $this->pTwoSymbol;
    }

    public function setPTwoSymbol(?string $pTwoSymbol): self
    {
        $this->pTwoSymbol = $pTwoSymbol;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getActivePlayer(): ?int
    {
        return $this->activePlayer;
    }

    public function setActivePlayer(int $activePlayer): self
    {
        $this->activePlayer = $activePlayer;

        return $this;
    }

    public function setIsCurrentPlayer(bool $currentPlayer): self
    {
        $this->isCurrentPlayer = $currentPlayer;

        return $this;
    }

    public function getIsCurrentPlayer(): ?bool
    {
        return $this->isCurrentPlayer;
    }

    public function getMoveCount(): ?int
    {
        return $this->moveCount;
    }

    public function setMoveCount(int $moveCount): self
    {
        $this->moveCount = $moveCount;

        return $this;
    }

    public function getWinner(): ?Player
    {
        return $this->winner;
    }

    public function setWinner(?Player $winner): self
    {
        $this->winner = $winner;

        return $this;
    }
}

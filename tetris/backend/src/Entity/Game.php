<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GameRepository")
 */
class Game
{
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Player")
     */
    private $activePlayer;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Board", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
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

    public function getActivePlayer(): ?Player
    {
        return $this->activePlayer;
    }

    public function setActivePlayer(?Player $activePlayer): self
    {
        $this->activePlayer = $activePlayer;

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
}

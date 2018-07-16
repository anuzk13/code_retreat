<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BoardRepository")
 */
class Board
{
    const NOUGHT = 'O';
    const CROSS = 'X';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="json_array")
     */
    private $positions;

    public function getId()
    {
        return $this->id;
    }

    public function getPositions()
    {
        return $this->positions;
    }

    public function setPositions($positions): self
    {
        $this->positions = $positions;

        return $this;
    }
}

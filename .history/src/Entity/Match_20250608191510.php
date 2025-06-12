<?php

namespace App\Entity;

use App\Repository\MatchRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MatchRepository::class)
 * @ORM\Table(name="matches")
 */
class Match
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Poule")
     * @ORM\JoinColumn(nullable=false)
     */
    private $poule;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Club")
     * @ORM\JoinColumn(nullable=false)
     */
    private $club1;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Club")
     * @ORM\JoinColumn(nullable=false)
     */
    private $club2;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPoule(): ?Poule
    {
        return $this->poule;
    }

    public function setPoule(?Poule $poule): self
    {
        $this->poule = $poule;
        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getClub1(): ?Club
    {
        return $this->club1;
    }

    public function setClub1(?Club $club1): self
    {
        $this->club1 = $club1;
        return $this;
    }

    public function getClub2(): ?Club
    {
        return $this->club2;
    }

    public function setClub2(?Club $club2): self
    {
        $this->club2 = $club2;
        return $this;
    }
}

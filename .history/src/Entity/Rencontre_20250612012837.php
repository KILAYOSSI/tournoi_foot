<?php

namespace App\Entity;

use App\Repository\RencontreRepository;
use App\Entity\Statistiquesmatch;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RencontreRepository::class)
 */
class Rencontre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Club::class, inversedBy="rencontres")
     */
    private $club1;

    /**
     * @ORM\ManyToOne(targetEntity=Club::class, inversedBy="rencontres")
     */
    private $club2;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateHeure;

    /**
     * @ORM\Column(type="integer", name="scoreclub1")
     */
    private $scoreclub1;

    /**
     * @ORM\Column(type="integer", name="scoreclub2")
     */
    private $scoreclub2;

    /**
     * @ORM\Column(type="boolean")
     */
    private $joue;

    /**
     * @ORM\OneToMany(targetEntity=Statistiquesmatch::class, mappedBy="rencontre")
     */
    private $statistiquematches;

    /**
     * @ORM\OneToMany(targetEntity=Vote::class, mappedBy="rencontre")
     */
    private $votes;

    public function __construct()
    {
        $this->statistiquematches = new ArrayCollection();
        $this->votes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateHeure(): ?\DateTimeInterface
    {
        return $this->dateHeure;
    }

    public function setDateHeure(\DateTimeInterface $dateHeure): self
    {
        $this->dateHeure = $dateHeure;
        return $this;
    }

    public function getScoreclub1(): ?int
    {
        return $this->scoreclub1;
    }

    public function setScoreclub1(int $scoreclub1): self
    {
        $this->scoreclub1 = $scoreclub1;
        return $this;
    }

    public function getScoreclub2(): ?int
    {
        return $this->scoreclub2;
    }

    public function setScoreclub2(int $scoreclub2): self
    {
        $this->scoreclub2 = $scoreclub2;
        return $this;
    }

    public function isJoue(): ?bool
    {
        return $this->joue;
    }

    public function setJoue(bool $joue): self
    {
        $this->joue = $joue;
        return $this;
    }

    /**
     * @return Collection<int, Statistiquesmatch>
     */
    public function getStatistiquesmatches(): Collection
    {
        return $this->statistiquematches;
    }

    public function addStatistiquesmatch(Statistiquesmatch $statistiquesmatch): self
    {
        if (!$this->statistiquematches->contains($statistiquesmatch)) {
            $this->statistiquematches[] = $statistiquesmatch;
            $statistiquesmatch->setRencontre($this);
        }
        return $this;
    }

    public function removeStatistiquesmatch(Statistiquesmatch $statistiquesmatch): self
    {
        if ($this->statistiquematches->removeElement($statistiquesmatch)) {
            if ($statistiquesmatch->getRencontre() === $this) {
                $statistiquesmatch->setRencontre(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, Vote>
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function addVote(Vote $vote): self
    {
        if (!$this->votes->contains($vote)) {
            $this->votes[] = $vote;
            $vote->setRencontre($this);
        }
        return $this;
    }

    public function removeVote(Vote $vote): self
    {
        if ($this->votes->removeElement($vote)) {
            if ($vote->getRencontre() === $this) {
                $vote->setRencontre(null);
            }
        }
        return $this;
    }
}
<?php

namespace App\Entity;

use App\Repository\ClassementPouleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClassementPouleRepository::class)
 */
class ClassementPoule
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Club::class, inversedBy="classementPoules")
     */
    private $club;

    /**
     * @ORM\ManyToOne(targetEntity=Poule::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $poule;

    /**
     * @ORM\Column(type="integer")
     */
    private $points;

    /**
     * @ORM\Column(type="integer")
     */
    private $butsPour;

    /**
     * @ORM\Column(type="integer")
     */
    private $butscontre;

    /**
     * @ORM\Column(type="integer")
     */
    private $goalaverage;

    /**
     * @ORM\Column(type="integer")
     */
    private $matchsjouer;

    /**
     * @ORM\Column(type="integer")
     */
    private $gagnes;

    /**
     * @ORM\Column(type="integer")
     */
    private $nuls;

    /**
     * @ORM\Column(type="integer")
     */
    private $perdus;

    /**
     * @ORM\Column(type="integer")
     */
    private $rang;

    /**
     * @ORM\Column(type="integer")
     */
    private $cartonsJaunes = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $cartonsRouges = 0;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClub(): ?Club
    {
        return $this->club;
    }

    public function setClub(?Club $club): self
    {
        $this->club = $club;

        return $this;
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

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(int $points): self
    {
        $this->points = $points;

        return $this;
    }

    public function getButsPour(): ?int
    {
        return $this->butsPour;
    }

    public function setButsPour(int $butsPour): self
    {
        $this->butsPour = $butsPour;

        return $this;
    }

    public function getButscontre(): ?int
    {
        return $this->butscontre;
    }

    public function setButscontre(int $butscontre): self
    {
        $this->butscontre = $butscontre;

        return $this;
    }

    public function getGoalaverage(): ?int
    {
        return $this->goalaverage;
    }

    public function setGoalaverage(int $goalaverage): self
    {
        $this->goalaverage = $goalaverage;

        return $this;
    }

    public function getMatchsjouer(): ?int
    {
        return $this->matchsjouer;
    }

    public function setMatchsjouer(int $matchsjouer): self
    {
        $this->matchsjouer = $matchsjouer;

        return $this;
    }

    public function getGagnes(): ?int
    {
        return $this->gagnes;
    }

    public function setGagnes(int $gagnes): self
    {
        $this->gagnes = $gagnes;

        return $this;
    }

    public function getNuls(): ?int
    {
        return $this->nuls;
    }

    public function setNuls(int $nuls): self
    {
        $this->nuls = $nuls;

        return $this;
    }

    public function getPerdus(): ?int
    {
        return $this->perdus;
    }

    public function setPerdus(int $perdus): self
    {
        $this->perdus = $perdus;

        return $this;
    }

    public function getRang(): ?int
    {
        return $this->rang;
    }

    public function setRang(int $rang): self
    {
        $this->rang = $rang;

        return $this;
    }
}

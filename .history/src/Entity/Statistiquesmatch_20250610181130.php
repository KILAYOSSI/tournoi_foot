<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="statistiques")
 */
class Statistiques
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity="Joueur")
     * @ORM\JoinColumn(nullable=false)
     */
    private Joueur $joueur;

    /**
     * @ORM\Column(type="integer")
     */
    private int $buts;

    /**
     * @ORM\Column(type="integer")
     */
    private int $passesDecisives;

    /**
     * @ORM\Column(type="integer")
     */
    private int $cleanSheets;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJoueur(): Joueur
    {
        return $this->joueur;
    }

    public function setJoueur(Joueur $joueur): self
    {
        $this->joueur = $joueur;
        return $this;
    }

    public function getButs(): int
    {
        return $this->buts;
    }

    public function setButs(int $buts): self
    {
        $this->buts = $buts;
        return $this;
    }

    public function getPassesDecisives(): int
    {
        return $this->passesDecisives;
    }

    public function setPassesDecisives(int $passesDecisives): self
    {
        $this->passesDecisives = $passesDecisives;
        return $this;
    }

    public function getCleanSheets(): int
    {
        return $this->cleanSheets;
    }

    public function setCleanSheets(int $cleanSheets): self
    {
        $this->cleanSheets = $cleanSheets;
        return $this;
    }
}

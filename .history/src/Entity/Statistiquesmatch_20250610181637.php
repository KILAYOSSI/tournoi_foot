<?php

namespace App\Entity;

use App\Repository\StatistiquematchRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StatistiquematchRepository::class)
 */
class Statistiquematch
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Rencontre::class, inversedBy="statistiquematches")
     */
    private $rencontre;

    /**
     * @ORM\ManyToOne(targetEntity=Joueur::class, inversedBy="statistiquematches")
     */
    private $joueur;

    /**
     * @ORM\Column(type="integer")
     */
    private $buts;

    /**
     * @ORM\Column(type="integer")
     */
    private $passes;

    /**
     * @ORM\Column(type="boolean")
     */
    private $cleansheet;

    /**
     * @ORM\Column(type="integer")
     */
    private $cartonsjaunes;

    /**
     * @ORM\Column(type="integer")
     */
    private $cartonsrouges;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRencontre(): ?Rencontre
    {
        return $this->rencontre;
    }

    public function setRencontre(?Rencontre $rencontre): self
    {
        $this->rencontre = $rencontre;

        return $this;
    }

    public function getJoueur(): ?Joueur
    {
        return $this->joueur;
    }

    public function setJoueur(?Joueur $joueur): self
    {
        $this->joueur = $joueur;

        return $this;
    }

    public function getButs(): ?int
    {
        return $this->buts;
    }

    public function setButs(int $buts): self
    {
        $this->buts = $buts;

        return $this;
    }

    public function getPasses(): ?int
    {
        return $this->passes;
    }

    public function setPasses(int $passes): self
    {
        $this->passes = $passes;

        return $this;
    }

    public function isCleansheet(): ?bool
    {
        return $this->cleansheet;
    }

    public function setCleansheet(bool $cleansheet): self
    {
        $this->cleansheet = $cleansheet;

        return $this;
    }

    public function getCartonsjaunes(): ?int
    {
        return $this->cartonsjaunes;
    }

    public function setCartonsjaunes(int $cartonsjaunes): self
    {
        $this->cartonsjaunes = $cartonsjaunes;

        return $this;
    }

    public function getCartonsrouges(): ?int
    {
        return $this->cartonsrouges;
    }

    public function setCartonsrouges(int $cartonsrouges): self
    {
        $this->cartonsrouges = $cartonsrouges;

        return $this;
    }
}

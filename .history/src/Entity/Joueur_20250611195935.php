<?php

namespace App\Entity;

use App\Repository\JoueurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=JoueurRepository::class)
 */
class Joueur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $poste;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @ORM\ManyToOne(targetEntity=Club::class, inversedBy="joueurs")
     */
    private $club;

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @ORM\OneToMany(targetEntity=Statistiquesmatch::class, mappedBy="joueur")
     */
    private $statistiquesmatches;

    /**
     * @ORM\OneToMany(targetEntity=Vote::class, mappedBy="joueur")
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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPoste(): ?string
    {
        return $this->poste;
    }

    public function setPoste(string $poste): self
    {
        $this->poste = $poste;

        return $this;
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

    /**
     * @return Collection<int, Statistiquematch>
     */
    public function getStatistiquematches(): Collection
    {
        return $this->statistiquematches;
    }

    public function addStatistiquematch(Statistiquematch $statistiquematch): self
    {
        if (!$this->statistiquematches->contains($statistiquematch)) {
            $this->statistiquematches[] = $statistiquematch;
            $statistiquematch->setJoueur($this);
        }

        return $this;
    }

    public function removeStatistiquematch(Statistiquematch $statistiquematch): self
    {
        if ($this->statistiquematches->removeElement($statistiquematch)) {
            // set the owning side to null (unless already changed)
            if ($statistiquematch->getJoueur() === $this) {
                $statistiquematch->setJoueur(null);
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
            $vote->setJoueur($this);
        }

        return $this;
    }

    public function removeVote(Vote $vote): self
    {
        if ($this->votes->removeElement($vote)) {
            // set the owning side to null (unless already changed)
            if ($vote->getJoueur() === $this) {
                $vote->setJoueur(null);
            }
        }

        return $this;
    }
}

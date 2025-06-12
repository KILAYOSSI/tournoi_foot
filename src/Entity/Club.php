<?php

namespace App\Entity;

use App\Repository\ClubRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClubRepository::class)
 */
class Club
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
    private $logo;

    /**
     * @ORM\ManyToOne(targetEntity=Poule::class, inversedBy="clubs")
     */
    private $poule;

    /**
     * @ORM\OneToMany(targetEntity=Joueur::class, mappedBy="club")
     */
    private $joueurs;

    /**
     * @ORM\OneToMany(targetEntity=Rencontre::class, mappedBy="club1")
     */
    private $rencontresClub1;

    /**
     * @ORM\OneToMany(targetEntity=Rencontre::class, mappedBy="club2")
     */
    private $rencontresClub2;

    /**
     * @ORM\OneToMany(targetEntity=Vote::class, mappedBy="club")
     */
    private $votes;

    public function __construct()
    {
        $this->joueurs = new ArrayCollection();
        $this->rencontresClub1 = new ArrayCollection();
        $this->rencontresClub2 = new ArrayCollection();
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

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): self
    {
        $this->logo = $logo;
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

    public function getJoueurs(): Collection
    {
        return $this->joueurs;
    }

    public function addJoueur(Joueur $joueur): self
    {
        if (!$this->joueurs->contains($joueur)) {
            $this->joueurs[] = $joueur;
            $joueur->setClub($this);
        }
        return $this;
    }

    public function removeJoueur(Joueur $joueur): self
    {
        if ($this->joueurs->removeElement($joueur)) {
            if ($joueur->getClub() === $this) {
                $joueur->setClub(null);
            }
        }
        return $this;
    }

    public function getRencontresClub1(): Collection
    {
        return $this->rencontresClub1;
    }

    public function addRencontresClub1(Rencontre $rencontre): self
    {
        if (!$this->rencontresClub1->contains($rencontre)) {
            $this->rencontresClub1[] = $rencontre;
            $rencontre->setClub1($this);
        }
        return $this;
    }

    public function removeRencontresClub1(Rencontre $rencontre): self
    {
        if ($this->rencontresClub1->removeElement($rencontre)) {
            if ($rencontre->getClub1() === $this) {
                $rencontre->setClub1(null);
            }
        }
        return $this;
    }

    public function getRencontresClub2(): Collection
    {
        return $this->rencontresClub2;
    }

    public function addRencontresClub2(Rencontre $rencontre): self
    {
        if (!$this->rencontresClub2->contains($rencontre)) {
            $this->rencontresClub2[] = $rencontre;
            $rencontre->setClub2($this);
        }
        return $this;
    }

    public function removeRencontresClub2(Rencontre $rencontre): self
    {
        if ($this->rencontresClub2->removeElement($rencontre)) {
            if ($rencontre->getClub2() === $this) {
                $rencontre->setClub2(null);
            }
        }
        return $this;
    }

    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function addVote(Vote $vote): self
    {
        if (!$this->votes->contains($vote)) {
            $this->votes[] = $vote;
            $vote->setClub($this);
        }
        return $this;
    }

    public function removeVote(Vote $vote): self
    {
        if ($this->votes->removeElement($vote)) {
            if ($vote->getClub() === $this) {
                $vote->setClub(null);
            }
        }
        return$this;
}
}

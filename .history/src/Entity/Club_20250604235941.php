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
    private $rencontres;

    /**
     * @ORM\OneToMany(targetEntity=ClassementPoule::class, mappedBy="club")
     */
    private $classementPoules;

    /**
     * @ORM\OneToMany(targetEntity=Vote::class, mappedBy="club")
     */
    private $votes;

    public function __construct()
    {
        $this->joueurs = new ArrayCollection();
        $this->rencontres = new ArrayCollection();
        $this->classementPoules = new ArrayCollection();
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

    /**
     * @return Collection<int, Joueur>
     */
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
            // set the owning side to null (unless already changed)
            if ($joueur->getClub() === $this) {
                $joueur->setClub(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Rencontre>
     */
    public function getRencontres(): Collection
    {
        return $this->rencontres;
    }

    public function addRencontre(Rencontre $rencontre): self
    {
        if (!$this->rencontres->contains($rencontre)) {
            $this->rencontres[] = $rencontre;
            $rencontre->setClub1($this);
        }

        return $this;
    }

    public function removeRencontre(Rencontre $rencontre): self
    {
        if ($this->rencontres->removeElement($rencontre)) {
            // set the owning side to null (unless already changed)
            if ($rencontre->getClub1() === $this) {
                $rencontre->setClub1(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ClassementPoule>
     */
    public function getClassementPoules(): Collection
    {
        return $this->classementPoules;
    }

    public function addClassementPoule(ClassementPoule $classementPoule): self
    {
        if (!$this->classementPoules->contains($classementPoule)) {
            $this->classementPoules[] = $classementPoule;
            $classementPoule->setClub($this);
        }

        return $this;
    }

    public function removeClassementPoule(ClassementPoule $classementPoule): self
    {
        if ($this->classementPoules->removeElement($classementPoule)) {
            // set the owning side to null (unless already changed)
            if ($classementPoule->getClub() === $this) {
                $classementPoule->setClub(null);
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
            $vote->setClub($this);
        }

        return $this;
    }

    public function removeVote(Vote $vote): self
    {
        if ($this->votes->removeElement($vote)) {
            // set the owning side to null (unless already changed)
            if ($vote->getClub() === $this) {
                $vote->setClub(null);
            }
        }

        return $this;
    }
}

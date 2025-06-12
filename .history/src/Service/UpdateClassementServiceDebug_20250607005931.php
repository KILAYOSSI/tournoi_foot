<?php

namespace App\Service;

use App\Entity\ClassementPoule;
use App\Repository\ClubRepository;
use App\Repository\RencontreRepository;
use App\Repository\ClassementPouleRepository;
use Doctrine\ORM\EntityManagerInterface;

class UpdateClassementServiceDebug extends UpdateClassementService
{
    private $classementPouleRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        ClubRepository $clubRepository,
        RencontreRepository $rencontreRepository,
        ClassementPouleRepository $classementPouleRepository
    ) {
        parent::__construct($entityManager, $clubRepository, $rencontreRepository, $classementPouleRepository);
        $this->classementPouleRepository = $classementPouleRepository;
    }

    public function updateClassements(): void
    {
        parent::updateClassements();

        // Ajout de logs pour debug
        $classements = $this->classementPouleRepository->findAll();
        foreach ($classements as $classement) {
            $club = $classement->getClub();
            $points = $classement->getPoints();
            $matchsJoues = $classement->getMatchsjouer();
            error_log("Classement club: " . $club->getNom() . " - Points: $points - Matchs joués: $matchsJoues");
        }
    }
}

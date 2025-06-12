<?php

namespace App\Command;

use App\Service\UpdateClassementService;
use App\Entity\ClassementPoule;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Console\Command\Command;

class UpdateClassementPouleCommand extends Command
{
    protected static $defaultName = 'app:update-classement-poule';

    private $entityManager;
    private $updateClassementService;

    public function __construct(EntityManagerInterface $entityManager, UpdateClassementService $updateClassementService)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->updateClassementService = $updateClassementService;
    }

    protected function configure()
    {
        $this
            ->setDescription('Nettoie les doublons dans la table classement_poule et met à jour les classements.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Début du nettoyage des doublons dans classement_poule...');

        // Supprimer les doublons dans classement_poule
        $classements = $this->entityManager->getRepository(ClassementPoule::class)->findAll();

        $seen = [];
        foreach ($classements as $classement) {
            $key = $classement->getClub()->getId() . '-' . $classement->getPoule()->getId();
            if (isset($seen[$key])) {
                $this->entityManager->remove($classement);
            } else {
                $seen[$key] = true;
            }
        }
        $this->entityManager->flush();

        $output->writeln('Doublons supprimés.');

        $output->writeln('Mise à jour des classements...');
        $this->updateClassementService->updateClassements();
        $output->writeln('Classements mis à jour avec succès.');

        return Command::SUCCESS;
    }
}

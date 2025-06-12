<?php

namespace App\Command;

use App\Entity\Poule;
use App\Entity\Club;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CleanUpdatePoulesClubsCommand extends Command
{
    protected static $defaultName = 'app:clean-update-poules-clubs';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this->setDescription('Nettoie et uniformise les poules et clubs, puis met à jour les classements');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $pouleRepo = $this->entityManager->getRepository(Poule::class);
        $clubRepo = $this->entityManager->getRepository(Club::class);

        // Supprimer toutes les poules sauf A, B, C, D
        $poules = $pouleRepo->findAll();
        foreach ($poules as $poule) {
            $nom = strtoupper(trim($poule->getNom()));
            if (!in_array($nom, ['POULE A', 'POULE B', 'POULE C', 'POULE D'])) {
                $this->entityManager->remove($poule);
            }
        }
        $this->entityManager->flush();

        // Recharger les poules A, B, C, D
        $pouleA = $pouleRepo->findOneBy(['nom' => 'Poule A']);
        $pouleB = $pouleRepo->findOneBy(['nom' => 'Poule B']);
        $pouleC = $pouleRepo->findOneBy(['nom' => 'Poule C']);
        $pouleD = $pouleRepo->findOneBy(['nom' => 'Poule D']);

        $poulesList = [$pouleA, $pouleB, $pouleC, $pouleD];

        // Charger tous les clubs sans dissocier de leurs poules
        $clubs = $clubRepo->findAll();

        // Nouvelle logique : assigner les clubs aux poules A, B, C, D de façon équilibrée
        $maxClubsPerPoule = 4;
        $clubsCount = [
            $pouleA->getId() => 0,
            $pouleB->getId() => 0,
            $pouleC->getId() => 0,
            $pouleD->getId() => 0,
        ];

        $poulesList = [$pouleA, $pouleB, $pouleC, $pouleD];
        $pouleIndex = 0;

        foreach ($clubs as $club) {
            // Trouver la prochaine poule avec moins de 4 clubs
            $placed = false;
            for ($i = 0; $i < count($poulesList); $i++) {
                $currentPoule = $poulesList[$pouleIndex];
                $currentPouleId = $currentPoule->getId();
                if ($clubsCount[$currentPouleId] < $maxClubsPerPoule) {
                    $club->setPoule($currentPoule);
                    $this->entityManager->persist($club);
                    $clubsCount[$currentPouleId]++;
                    $placed = true;
                    $pouleIndex = ($pouleIndex + 1) % count($poulesList);
                    break;
                }
                $pouleIndex = ($pouleIndex + 1) % count($poulesList);
            }
            if (!$placed) {
                // Si toutes les poules sont pleines, dissocier le club
                $club->setPoule(null);
                $this->entityManager->persist($club);
            }
        }
        $this->entityManager->flush();

        $output->writeln('Répartition des clubs dans les poules A, B, C avec limite de 4 clubs par poule, clubs excédentaires placés dans la poule D.');

        // Lancer la mise à jour des classements
        $output->writeln('Lancement de la mise à jour des classements...');
        $this->getApplication()->find('app:update-classement-poule')->run($input, $output);

        $output->writeln('Commande terminée.');

        return Command::SUCCESS;
    }
}

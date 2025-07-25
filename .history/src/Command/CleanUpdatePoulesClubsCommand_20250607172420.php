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

        // Dissocier tous les clubs de leurs poules pour repartir à zéro
        $clubs = $clubRepo->findAll();
        foreach ($clubs as $club) {
            $club->setPoule(null);
            $this->entityManager->persist($club);
        }
        $this->entityManager->flush();

        // Initialiser un tableau pour compter les clubs par poule (A, B, C)
        $clubsCount = [
            $pouleA->getId() => 0,
            $pouleB->getId() => 0,
            $pouleC->getId() => 0,
        ];

        // Répartir les clubs dans les poules A, B, C en limitant à 4 clubs par poule
        // Les clubs restants seront assignés à la poule D
        foreach ($clubs as $club) {
            $assigned = false;
            foreach ([$pouleA, $pouleB, $pouleC] as $poule) {
                if ($clubsCount[$poule->getId()] < 4) {
                    $club->setPoule($poule);
                    $this->entityManager->persist($club);
                    $clubsCount[$poule->getId()]++;
                    $assigned = true;
                    break;
                }
            }
            if (!$assigned) {
                // Assignation à la poule D pour les clubs restants
                $club->setPoule($pouleD);
                $this->entityManager->persist($club);
            }
        }
        $this->entityManager->flush();

        $output->writeln('Répartition des clubs dans les poules A, B, C, D effectuée avec limite de 4 clubs par poule.');

        // Lancer la mise à jour des classements
        $output->writeln('Lancement de la mise à jour des classements...');
        $this->getApplication()->find('app:update-classement-poule')->run($input, $output);

        $output->writeln('Commande terminée.');

        return Command::SUCCESS;
    }
}

<?php

namespace App\Command;

use App\Entity\ClassementPoule;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CleanDuplicateClassementPouleCommand extends Command
{
    public const NAME = 'app:clean-duplicate-classement-poule';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Nettoie les doublons dans la table classement_poule en conservant une seule entrée par club et poule.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Début du nettoyage des doublons dans classement_poule...');

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

        return Command::SUCCESS;
    }
}

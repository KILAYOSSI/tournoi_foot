<?php

namespace App\Command;

use App\Entity\ClassementPoule;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CleanDuplicateClassementPouleCommand extends Command
{
    protected static \$defaultName = 'app:clean-duplicate-classement-poule';

    private \$entityManager;

    public function __construct(EntityManagerInterface \$entityManager)
    {
        parent::__construct();
        \$this->entityManager = \$entityManager;
    }

    protected function configure()
    {
        \$this
            ->setDescription('Nettoie les doublons dans la table classement_poule en conservant une seule entrée par club et poule.');
    }

    protected function execute(InputInterface \$input, OutputInterface \$output): int
    {
        \$classements = \$this->entityManager->getRepository(ClassementPoule::class)->findAll();

        \$seen = [];
        \$toRemove = [];

        foreach (\$classements as \$classement) {
            \$key = \$classement->getClub()->getId() . '-' . \$classement->getPoule()->getId();
            if (isset(\$seen[\$key])) {
                // Doublon détecté, à supprimer
                \$toRemove[] = \$classement;
            } else {
                \$seen[\$key] = true;
            }
        }

        foreach (\$toRemove as \$dup) {
            \$this->entityManager->remove(\$dup);
        }

        \$this->entityManager->flush();

        \$output->writeln(sprintf('Nettoyage des doublons terminé. %d doublons supprimés.', count(\$toRemove)));

        return Command::SUCCESS;
    }
}

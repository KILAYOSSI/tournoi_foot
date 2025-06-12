<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CleanDuplicateClassementPouleCommand extends Command
{
    protected static $defaultName = 'app:clean-duplicate-classement-poule';

    protected function configure(): void
    {
        $this->setDescription('Nettoie les doublons dans la table classement_poule');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Cette commande a été supprimée car la table classement_poule n\'existe plus.');

        return Command::SUCCESS;
    }
}

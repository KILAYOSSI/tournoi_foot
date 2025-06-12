<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateClassementPouleCommand extends Command
{
    protected static $defaultName = 'app:update-classement-poule';

    protected function configure(): void
    {
        $this->setDescription('Commande obsolète : la table classement_poule a été supprimée.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Cette commande est obsolète car la table classement_poule n\'existe plus.');

        return Command::SUCCESS;
    }
}

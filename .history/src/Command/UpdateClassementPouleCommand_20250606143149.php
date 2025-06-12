<?php

namespace App\Command;

use App\Entity\ClassementPoule;
use App\Service\UpdateClassementService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateClassementPouleCommand extends Command
{
    protected static $defaultName = 'app:update-classement-poule';

    private $updateClassementService;

    public function __construct(UpdateClassementService $updateClassementService)
    {
        parent::__construct();
        $this->updateClassementService = $updateClassementService;
    }

    protected function configure()
    {
        $this->setDescription('Met à jour les classements par poule en fonction des résultats des matchs');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->updateClassementService->updateClassements();

        $output->writeln('Classements mis à jour avec succès.');

        return Command::SUCCESS;
    }
}

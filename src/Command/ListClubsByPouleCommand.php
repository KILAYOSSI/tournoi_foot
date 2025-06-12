<?php

namespace App\Command;

use App\Repository\ClubRepository;
use App\Repository\PouleRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListClubsByPouleCommand extends Command
{
    protected static $defaultName = 'app:list-clubs-by-poule';

    private $clubRepository;
    private $pouleRepository;

    public function __construct(ClubRepository $clubRepository, PouleRepository $pouleRepository)
    {
        parent::__construct();
        $this->clubRepository = $clubRepository;
        $this->pouleRepository = $pouleRepository;
    }

    protected function configure()
    {
        $this
            ->setDescription('Liste les clubs d\'une poule donnée')
            ->addArgument('poule', InputArgument::REQUIRED, 'Nom de la poule (ex: Poule A)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $pouleName = $input->getArgument('poule');
        $poule = $this->pouleRepository->findOneBy(['nom' => $pouleName]);

        if (!$poule) {
            $output->writeln("Poule '$pouleName' non trouvée.");
            return Command::FAILURE;
        }

        $clubs = $this->clubRepository->findBy(['poule' => $poule]);

        if (empty($clubs)) {
            $output->writeln("Aucun club trouvé pour la poule '$pouleName'.");
            return Command::SUCCESS;
        }

        $output->writeln("Clubs dans la poule '$pouleName' :");
        foreach ($clubs as $club) {
            $output->writeln("- " . $club->getNom());
        }

        return Command::SUCCESS;
    }
}

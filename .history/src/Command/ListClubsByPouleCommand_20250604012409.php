<?php

namespace App\Command;

use App\Repository\ClubRepository;
use App\Repository\PouleRepository;
use Symfony\Component\Console\Command\Command;
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
        $this->setDescription('Liste les clubs par poule');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $poules = $this->pouleRepository->findAll();

        foreach ($poules as $poule) {
            $output->writeln('Poule: ' . $poule->getNom());
            $clubs = $this->clubRepository->findBy(['poule' => $poule]);

            if (empty($clubs)) {
                $output->writeln('  Aucun club trouvé');
            } else {
                foreach ($clubs as $club) {
                    $output->writeln('  - ' . $club->getNom());
                }
            }
            $output->writeln('');
        }

        return Command::SUCCESS;
    }
}

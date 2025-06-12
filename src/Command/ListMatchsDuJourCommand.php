<?php

namespace App\Command;

use App\Repository\RencontreRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListMatchsDuJourCommand extends Command
{
    protected static $defaultName = 'app:list-matchs-du-jour';

    private $rencontreRepository;

    public function __construct(RencontreRepository $rencontreRepository)
    {
        parent::__construct();
        $this->rencontreRepository = $rencontreRepository;
    }

    protected function configure()
    {
        $this->setDescription('Liste les matchs du jour non joués');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $now = new \DateTimeImmutable('now');
        $startOfDay = $now->setTime(0, 0, 0);
        $endOfDay = $now->setTime(23, 59, 59);

        $matchsDuJour = $this->rencontreRepository->createQueryBuilder('r')
            ->where('r.dateHeure BETWEEN :start AND :end')
            // Suppression de la condition sur 'joue' pour lister tous les matchs du jour
            //->andWhere('r.joue = false')
            ->setParameter('start', $startOfDay)
            ->setParameter('end', $endOfDay)
            ->orderBy('r.dateHeure', 'ASC')
            ->getQuery()
            ->getResult();

        if (empty($matchsDuJour)) {
            $output->writeln('Aucun match du jour non joué trouvé.');
        } else {
            foreach ($matchsDuJour as $match) {
                $output->writeln(sprintf(
                    'Match ID: %d, DateHeure: %s, Club1: %s, Club2: %s',
                    $match->getId(),
                    $match->getDateHeure()->format('Y-m-d H:i:s'),
                    $match->getClub1() ? $match->getClub1()->getNom() : 'N/A',
                    $match->getClub2() ? $match->getClub2()->getNom() : 'N/A'
                ));
            }
        }

        return Command::SUCCESS;
    }
}

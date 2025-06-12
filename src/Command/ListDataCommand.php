<?php

namespace App\Command;

use App\Repository\ClubRepository;
use App\Repository\JoueurRepository;
use App\Repository\MatchRepository;
use App\Repository\VoteRepository;
use App\Repository\ClassementPouleRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListDataCommand extends Command
{
    protected static $defaultName = 'app:list-data';

    private $clubRepository;
    private $joueurRepository;
    private $matchRepository;
    private $voteRepository;
    private $classementPouleRepository;

    public function __construct(
        ClubRepository $clubRepository,
        JoueurRepository $joueurRepository,
        MatchRepository $matchRepository,
        VoteRepository $voteRepository,
        ClassementPouleRepository $classementPouleRepository
    ) {
        parent::__construct();
        $this->clubRepository = $clubRepository;
        $this->joueurRepository = $joueurRepository;
        $this->matchRepository = $matchRepository;
        $this->voteRepository = $voteRepository;
        $this->classementPouleRepository = $classementPouleRepository;
    }

    protected function configure()
    {
        $this->setDescription('Liste les données principales de la base (clubs, joueurs, matchs, votes, classements)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('--- Clubs ---');
        $clubs = $this->clubRepository->findAll();
        foreach ($clubs as $club) {
            $output->writeln(sprintf('ID: %d, Nom: %s', $club->getId(), $club->getNom()));
        }

        $output->writeln("\n--- Joueurs ---");
        $joueurs = $this->joueurRepository->findAll();
        foreach ($joueurs as $joueur) {
            $output->writeln(sprintf('ID: %d, Nom: %s, Poste: %s, Club: %s',
                $joueur->getId(),
                $joueur->getNom(),
                $joueur->getPoste(),
                $joueur->getClub() ? $joueur->getClub()->getNom() : 'N/A'
            ));
        }

        $output->writeln("\n--- Matchs ---");
        $matchs = $this->matchRepository->findAll();
        foreach ($matchs as $match) {
            $output->writeln(sprintf('ID: %d, Date: %s, Poule: %s',
                $match->getId(),
                $match->getDate() ? $match->getDate()->format('Y-m-d H:i') : 'N/A',
                $match->getPoule() ? $match->getPoule()->getNom() : 'N/A'
            ));
        }

        $output->writeln("\n--- Votes ---");
        $votes = $this->voteRepository->findAll();
        foreach ($votes as $vote) {
            $output->writeln(sprintf('ID: %d, Joueur: %s, Nombre: %d',
                $vote->getId(),
                $vote->getJoueur() ? $vote->getJoueur()->getNom() : 'N/A',
                $vote->getNombre()
            ));
        }

        $output->writeln("\n--- Classements par Poule ---");
        $classements = $this->classementPouleRepository->findAll();
        foreach ($classements as $classement) {
            $output->writeln(sprintf('ID: %d, Poule: %s, Club: %s, Points: %d',
                $classement->getId(),
                $classement->getPoule() ? $classement->getPoule()->getNom() : 'N/A',
                $classement->getClub() ? $classement->getClub()->getNom() : 'N/A',
                $classement->getPoints()
            ));
        }

        return Command::SUCCESS;
    }
}

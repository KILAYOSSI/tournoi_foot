<?php

namespace App\Service;

use App\Repository\ClubRepository;
use App\Repository\RencontreRepository;
use Doctrine\ORM\EntityManagerInterface;

class UpdateClassementService
{
    private $entityManager;
    private $clubRepository;
    private $rencontreRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        ClubRepository $clubRepository,
        RencontreRepository $rencontreRepository
    ) {
        $this->entityManager = $entityManager;
        $this->clubRepository = $clubRepository;
        $this->rencontreRepository = $rencontreRepository;
    }

    public function updateClassements(): void
    {
        $clubs = $this->clubRepository->findAll();

        // Initialiser un tableau pour stocker les stats par club
        $stats = [];

        foreach ($clubs as $club) {
            $stats[$club->getId()] = [
                'club' => $club,
                'points' => 0,
                'butsPour' => 0,
                'butscontre' => 0,
                'goalaverage' => 0,
                'matchsjouer' => 0,
                'gagnes' => 0,
                'nuls' => 0,
                'perdus' => 0,
                'pouleId' => $club->getPoule() ? $club->getPoule()->getId() : null,
                'matchsJouesIds' => [], // Pour compter les matchs joués
            ];
        }

        $rencontres = $this->rencontreRepository->findBy(['joue' => true], ['dateHeure' => 'ASC']);

        foreach ($rencontres as $rencontre) {
            if ($rencontre->getScoreclub1() === null || $rencontre->getScoreclub2() === null) {
                continue; // Ignorer les matchs sans score
            }

            $club1Id = $rencontre->getClub1()->getId();
            $club2Id = $rencontre->getClub2()->getId();

            // Eviter de compter plusieurs fois le même match
            $matchId = $rencontre->getId();
            if (in_array($matchId, $stats[$club1Id]['matchsJouesIds']) || in_array($matchId, $stats[$club2Id]['matchsJouesIds'])) {
                continue;
            }

            $stats[$club1Id]['matchsJouesIds'][] = $matchId;
            $stats[$club2Id]['matchsJouesIds'][] = $matchId;

            $score1 = $rencontre->getScoreclub1();
            $score2 = $rencontre->getScoreclub2();

            // Mise à jour des buts
            $stats[$club1Id]['butsPour'] += $score1;
            $stats[$club1Id]['butscontre'] += $score2;
            $stats[$club2Id]['butsPour'] += $score2;
            $stats[$club2Id]['butscontre'] += $score1;

            // Matchs joués
            $stats[$club1Id]['matchsjouer']++;
            $stats[$club2Id]['matchsjouer']++;

            // Résultats
            if ($score1 > $score2) {
                $stats[$club1Id]['points'] += 3;
                $stats[$club1Id]['gagnes']++;
                $stats[$club2Id]['perdus']++;
            } elseif ($score1 < $score2) {
                $stats[$club2Id]['points'] += 3;
                $stats[$club2Id]['gagnes']++;
                $stats[$club1Id]['perdus']++;
            } else {
                $stats[$club1Id]['points'] += 1;
                $stats[$club2Id]['points'] += 1;
                $stats[$club1Id]['nuls']++;
                $stats[$club2Id]['nuls']++;
            }
        }

        // Calcul de la différence de buts
        foreach ($stats as &$stat) {
            $stat['goalaverage'] = $stat['butsPour'] - $stat['butscontre'];
        }

        // Ajout des logs de debug pour chaque club
        $logFile = __DIR__ . '/../../var/log/update_classement_debug.log';
        file_put_contents($logFile, "Stats calculées par club :\n", FILE_APPEND);
        foreach ($stats as $stat) {
            $clubName = $stat['club']->getNom();
            $points = $stat['points'];
            $butsPour = $stat['butsPour'];
            $butscontre = $stat['butscontre'];
            $matchsjouer = $stat['matchsjouer'];
            $gagnes = $stat['gagnes'];
            $nuls = $stat['nuls'];
            $perdus = $stat['perdus'];
            $goalaverage = $stat['goalaverage'];
            file_put_contents($logFile, "Club: $clubName, Points: $points, Buts Pour: $butsPour, Buts Contre: $butscontre, Matchs Joués: $matchsjouer, Gagnés: $gagnes, Nuls: $nuls, Perdus: $perdus, Goal Average: $goalaverage\n", FILE_APPEND);
        }

        // Grouper les stats par poule
        $statsParPoule = [];
        foreach ($stats as $stat) {
            if ($stat['pouleId'] === null) {
                continue;
            }
            if (!isset($statsParPoule[$stat['pouleId']])) {
                $statsParPoule[$stat['pouleId']] = [];
            }
            $statsParPoule[$stat['pouleId']][] = $stat;
        }

        // Fonction pour trier avec confrontation directe
        $sortWithConfrontation = function (&$clubs, $rencontres) {
            usort($clubs, function ($a, $b) use ($rencontres) {
                // 1. Points
                if ($a['points'] !== $b['points']) {
                    return $b['points'] <=> $a['points'];
                }
                // 2. Différence de buts
                if ($a['goalaverage'] !== $b['goalaverage']) {
                    return $b['goalaverage'] <=> $a['goalaverage'];
                }
                // 3. Buts marqués
                if ($a['butsPour'] !== $b['butsPour']) {
                    return $b['butsPour'] <=> $a['butsPour'];
                }
                // 4. Confrontation directe
                $pointsA = 0;
                $pointsB = 0;
                foreach ($rencontres as $match) {
                    $club1Id = $match->getClub1()->getId();
                    $club2Id = $match->getClub2()->getId();
                    if (($club1Id === $a['club']->getId() && $club2Id === $b['club']->getId()) ||
                        ($club1Id === $b['club']->getId() && $club2Id === $a['club']->getId())) {
                        $score1 = $match->getScoreclub1();
                        $score2 = $match->getScoreclub2();
                        if ($club1Id === $a['club']->getId()) {
                            if ($score1 > $score2) {
                                $pointsA += 3;
                            } elseif ($score1 === $score2) {
                                $pointsA += 1;
                                $pointsB += 1;
                            } else {
                                $pointsB += 3;
                            }
                        } else {
                            if ($score2 > $score1) {
                                $pointsA += 3;
                            } elseif ($score1 === $score2) {
                                $pointsA += 1;
                                $pointsB += 1;
                            } else {
                                $pointsB += 3;
                            }
                        }
                    }
                }
                if ($pointsA !== $pointsB) {
                    return $pointsB <=> $pointsA;
                }
                return 0;
            });
        };

        // Appliquer le tri avec confrontation directe par poule
        foreach ($statsParPoule as &$statsPoule) {
            $sortWithConfrontation($statsPoule, $rencontres);
        }
}

<?php

namespace App\Controller;

use App\Repository\PouleRepository;
use App\Repository\ClubRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MatchController extends AbstractController
{
    /**
     * @Route("/match-du-jour/", name="match_du_jour")
     */
    public function matchDuJour(PouleRepository $pouleRepository, ClubRepository $clubRepository): Response
    {
        $matchRepository = $this->getDoctrine()->getRepository(\App\Entity\Match::class);
        $rencontreRepository = $this->getDoctrine()->getRepository(\App\Entity\Rencontre::class);

        $timezone = new \DateTimeZone('Europe/Paris');
        $aujourdhui = new \DateTime('now', $timezone);

        // Début de la journée (00:00:00)
        $debutJournee = (clone $aujourdhui)->setTime(0, 0, 0);
        // Fin de la journée (23:59:59)
        $finJournee = (clone $aujourdhui)->setTime(23, 59, 59);

        $matchsDuJour = $matchRepository->createQueryBuilder('m')
            ->where('m.date >= :debutJournee')
            ->andWhere('m.date <= :finJournee')
            ->setParameter('debutJournee', $debutJournee)
            ->setParameter('finJournee', $finJournee)
            ->orderBy('m.date', 'ASC')
            ->getQuery()
            ->getResult();

        $matchsResultats = $rencontreRepository->createQueryBuilder('r')
            ->where('r.joue = :joue')
            ->setParameter('joue', true)
            ->orderBy('r.dateHeure', 'DESC')
            ->getQuery()
            ->getResult();

        // Récupérer les poules A, B, C, D
        $poulesEntities = $pouleRepository->findBy(['nom' => ['Poule A', 'Poule B', 'Poule C', 'Poule D']]);

        $poules = [];

        foreach ($poulesEntities as $poule) {
            $clubs = $clubRepository->findBy(['poule' => $poule]);

            // Initialiser le classement
            $classement = [];

            // Initialiser les stats pour chaque club
            foreach ($clubs as $club) {
                $classement[$club->getId()] = [
                    'club' => $club,
                    'matchsjouer' => 0,
                    'gagnes' => 0,
                    'nuls' => 0,
                    'perdus' => 0,
                    'butsPour' => 0,
                    'butscontre' => 0,
                    'goalaverage' => 0,
                    'points' => 0,
                    'cartonsJaunes' => 0,
                    'cartonsRouges' => 0,
                ];
            }

            // Récupérer les rencontres jouées impliquant ces clubs
            $rencontreRepository = $this->getDoctrine()->getRepository(\App\Entity\Rencontre::class);
            $rencontres = $rencontreRepository->createQueryBuilder('r')
                ->where('r.joue = :joue')
                ->setParameter('joue', true)
                ->getQuery()
                ->getResult();

            // Calculer les stats à partir des rencontres
            foreach ($rencontres as $rencontre) {
                $club1 = $rencontre->getClub1();
                $club2 = $rencontre->getClub2();

                if (!isset($classement[$club1->getId()]) || !isset($classement[$club2->getId()])) {
                    continue; // Ignorer si un club n'est pas dans la poule
                }

                $score1 = $rencontre->getScoreclub1();
                $score2 = $rencontre->getScoreclub2();

                // Mise à jour matchs joués
                $classement[$club1->getId()]['matchsjouer']++;
                $classement[$club2->getId()]['matchsjouer']++;

                // Mise à jour buts pour et contre
                $classement[$club1->getId()]['butsPour'] += $score1;
                $classement[$club1->getId()]['butscontre'] += $score2;
                $classement[$club2->getId()]['butsPour'] += $score2;
                $classement[$club2->getId()]['butscontre'] += $score1;

                // Mise à jour cartons à partir des statistiques de match
                foreach ($rencontre->getStatistiquesmatchs() as $stat) {
                    $joueur = $stat->getJoueur();
                    if ($joueur && $joueur->getClub()->getId() === $club1->getId()) {
                        $classement[$club1->getId()]['cartonsJaunes'] += $stat->getCartonsjaunes() ?? 0;
                        $classement[$club1->getId()]['cartonsRouges'] += $stat->getCartonsrouges() ?? 0;
                    } elseif ($joueur && $joueur->getClub()->getId() === $club2->getId()) {
                        $classement[$club2->getId()]['cartonsJaunes'] += $stat->getCartonsjaunes() ?? 0;
                        $classement[$club2->getId()]['cartonsRouges'] += $stat->getCartonsrouges() ?? 0;
                    }
                }

                // Résultat du match
                if ($score1 > $score2) {
                    $classement[$club1->getId()]['gagnes']++;
                    $classement[$club2->getId()]['perdus']++;
                } elseif ($score1 < $score2) {
                    $classement[$club2->getId()]['gagnes']++;
                    $classement[$club1->getId()]['perdus']++;
                } else {
                    $classement[$club1->getId()]['nuls']++;
                    $classement[$club2->getId()]['nuls']++;
                }
            }

            // Calculer goalaverage et points
            foreach ($classement as &$equipe) {
                $equipe['goalaverage'] = $equipe['butsPour'] - $equipe['butscontre'];
                $equipe['points'] = $equipe['gagnes'] * 3 + $equipe['nuls'];
            }

            // Trier par points, goalaverage, butsPour
            usort($classement, function ($a, $b) {
                if ($b['points'] !== $a['points']) {
                    return $b['points'] - $a['points'];
                }
                if ($b['goalaverage'] !== $a['goalaverage']) {
                    return $b['goalaverage'] - $a['goalaverage'];
                }
                return $b['butsPour'] - $a['butsPour'];
            });

            // Ajouter le rang
            $rang = 1;
            foreach ($classement as &$equipe) {
                $equipe['rang'] = $rang++;
            }

            $poules[$poule->getNom()] = $classement;
        }

        return $this->render('pages/match_du_jour.html.twig', [
            'matchsDuJour' => $matchsDuJour,
            'matchsResultats' => $matchsResultats,
            'poules' => $poules,
        ]);
    }
}

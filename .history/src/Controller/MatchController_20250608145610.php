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

            // Calculer les points et trier les clubs
            $classement = [];

            foreach ($clubs as $club) {
                // Supposons que les méthodes getGagnes(), getNuls(), getPerdus(), getButsPour(), getButsContre(), getCartonsJaunes(), getCartonsRouges() existent dans Club ou entité liée
                $points = $club->getGagnes() * 3 + $club->getNuls();
                $goalaverage = $club->getButsPour() - $club->getButsContre();

                $classement[] = [
                    'club' => $club,
                    'matchsjouer' => $club->getMatchsJoues(),
                    'gagnes' => $club->getGagnes(),
                    'nuls' => $club->getNuls(),
                    'perdus' => $club->getPerdus(),
                    'butsPour' => $club->getButsPour(),
                    'butscontre' => $club->getButsContre(),
                    'goalaverage' => $goalaverage,
                    'points' => $points,
                    'cartonsJaunes' => $club->getCartonsJaunes(),
                    'cartonsRouges' => $club->getCartonsRouges(),
                ];
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

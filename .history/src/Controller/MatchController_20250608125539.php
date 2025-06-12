<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MatchController extends AbstractController
{
    /**
     * @Route("/match-du-jour/", name="match_du_jour")
     */
    public function matchDuJour(): Response
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

        return $this->render('pages/match_du_jour_clean.html.twig', [
            'matchsDuJour' => $matchsDuJour,
            'matchsResultats' => $matchsResultats,
        ]);
    }
}

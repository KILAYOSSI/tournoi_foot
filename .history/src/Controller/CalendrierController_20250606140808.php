<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendrierController extends AbstractController
{
    /**
     * @Route("/calendrier", name="calendrier")
     */
    public function index(): Response
    {
        $matchRepository = $this->getDoctrine()->getRepository(\App\Entity\Match::class);
        $rencontreRepository = $this->getDoctrine()->getRepository(\App\Entity\Rencontre::class);

        $timezone = new \DateTimeZone('Europe/Paris');
        $aujourdhui = new \DateTime('now', $timezone);
        $matchsAVenir = $matchRepository->createQueryBuilder('m')
            ->where('m.date > :now')
            ->setParameter('now', $aujourdhui)
            ->orderBy('m.date', 'ASC')
            ->getQuery()
            ->getResult();

        $matchsResultats = $rencontreRepository->createQueryBuilder('r')
            ->where('r.joue = :joue')
            ->setParameter('joue', true)
            ->orderBy('r.dateHeure', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('pages/calendrier.html.twig', [
            'matchsAVenir' => $matchsAVenir,
            'matchsResultats' => $matchsResultats,
        ]);
    }
}

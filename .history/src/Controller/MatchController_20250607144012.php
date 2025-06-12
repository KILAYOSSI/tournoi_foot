<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PouleRepository;

class MatchController extends AbstractController
{
    private $pouleRepository;
    private $updateClassementService;
    private $entityManager;

    public function __construct(PouleRepository $pouleRepository, \App\Service\UpdateClassementService $updateClassementService, \Doctrine\ORM\EntityManagerInterface $entityManager)
    {
        $this->pouleRepository = $pouleRepository;
        $this->updateClassementService = $updateClassementService;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/match-du-jour", name="match_du_jour")
     */
    public function matchDuJour(): Response
    {
        $matchRepository = $this->getDoctrine()->getRepository(\App\Entity\Match::class);
        $rencontreRepository = $this->getDoctrine()->getRepository(\App\Entity\Rencontre::class);

        $matchsDuJour = $matchRepository->findMatchsDuJour();

        $timezone = new \DateTimeZone('Europe/Paris');
        $today = new \DateTime('now', $timezone);
        $startOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', $today->format('Y-m-d') . ' 00:00:00', $timezone);
        $endOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', $today->format('Y-m-d') . ' 23:59:59', $timezone);

        $matchsResultats = $rencontreRepository->createQueryBuilder('r')
            ->where('r.dateHeure >= :startOfDay')
            ->andWhere('r.dateHeure <= :endOfDay')
            ->setParameter('startOfDay', $startOfDay)
            ->setParameter('endOfDay', $endOfDay)
            ->orderBy('r.dateHeure', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('pages/match_du_jour.html.twig', [
            'matchsDuJour' => $matchsDuJour,
            'matchsResultats' => $matchsResultats,
        ]);
    }
}

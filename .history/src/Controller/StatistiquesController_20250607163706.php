<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatistiquesController extends AbstractController
{
    /**
     * @Route("/statistiques", name="statistiques")
     */
    public function index(): Response
    {
        // Récupérer les données nécessaires pour les statistiques
        $classements = $this->getDoctrine()->getRepository(\App\Entity\ClassementPoule::class)->findAll();

        $joueursStats = $this->getDoctrine()->getRepository(\App\Entity\Statistiquematch::class)->createQueryBuilder('s')
            ->select('IDENTITY(s.joueur) as joueurId, SUM(s.buts) as totalButs, SUM(s.passes) as totalPasses, SUM(s.cartonsjaunes) as totalCartonsJaunes, SUM(s.cartonsrouges) as totalCartonsRouges')
            ->groupBy('s.joueur')
            ->orderBy('totalButs', 'DESC')
            ->getQuery()
            ->getResult();

        // Charger les entités Joueur correspondantes
        $joueurRepository = $this->getDoctrine()->getRepository(\App\Entity\Joueur::class);
        foreach ($joueursStats as &$stat) {
            if ($stat['joueurId'] !== null) {
                $joueur = $joueurRepository->find($stat['joueurId']);
                $stat['joueur'] = $joueur !== null ? $joueur : null;
            } else {
                $stat['joueur'] = null;
            }
        }

        // Calculer topGoalAssists (buts + passes)
        // Remplacé par topScorers, topAssisters et topGoalAssists séparés

        // Top buteurs
        $topScorers = $this->getDoctrine()->getRepository(\App\Entity\Statistiquematch::class)->createQueryBuilder('s')
            ->select('IDENTITY(s.joueur) as joueurId, SUM(s.buts) as totalButs')
            ->groupBy('s.joueur')
            ->orderBy('totalButs', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();

        foreach ($topScorers as &$stat) {
            if ($stat['joueurId'] !== null) {
                $joueur = $joueurRepository->find($stat['joueurId']);
                $stat['joueur'] = $joueur !== null ? $joueur : null;
            } else {
                $stat['joueur'] = null;
            }
        }

        // Top passeurs
        $topAssisters = $this->getDoctrine()->getRepository(\App\Entity\Statistiquematch::class)->createQueryBuilder('s')
            ->select('IDENTITY(s.joueur) as joueurId, SUM(s.passes) as totalPasses')
            ->groupBy('s.joueur')
            ->orderBy('totalPasses', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();

        foreach ($topAssisters as &$stat) {
            if ($stat['joueurId'] !== null) {
                $joueur = $joueurRepository->find($stat['joueurId']);
                $stat['joueur'] = $joueur !== null ? $joueur : null;
            } else {
                $stat['joueur'] = null;
            }
        }

        // Top G/A (buts + passes)
        $topGoalAssists = $this->getDoctrine()->getRepository(\App\Entity\Statistiquematch::class)->createQueryBuilder('s')
            ->select('IDENTITY(s.joueur) as joueurId, SUM(s.buts + s.passes) as goalsAssists')
            ->groupBy('s.joueur')
            ->orderBy('goalsAssists', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();

        foreach ($topGoalAssists as &$stat) {
            if ($stat['joueurId'] !== null) {
                $joueur = $joueurRepository->find($stat['joueurId']);
                $stat['joueur'] = $joueur !== null ? $joueur : null;
            } else {
                $stat['joueur'] = null;
            }
        }

        // Récupérer les meilleurs gardiens (clean sheets)
        $topCleanSheets = $this->getDoctrine()->getRepository(\App\Entity\Statistiquematch::class)->createQueryBuilder('s')
            ->select('IDENTITY(s.joueur) as joueurId, SUM(CASE WHEN s.cleansheet = true THEN 1 ELSE 0 END) as cleanSheets')
            ->groupBy('s.joueur')
            ->orderBy('cleanSheets', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();

        // Charger les entités Joueur correspondantes pour clean sheets
        foreach ($topCleanSheets as &$stat) {
            if ($stat['joueurId'] !== null) {
                $joueur = $joueurRepository->find($stat['joueurId']);
                $stat['joueur'] = $joueur !== null ? $joueur : null;
            } else {
                $stat['joueur'] = null;
            }
        }

        return $this->render('pages/statistiques.html.twig', [
            'classements' => $classements,
            'joueursStats' => $joueursStats,
            'topScorers' => $topScorers,
            'topAssisters' => $topAssisters,
            'topGoalAssists' => $topGoalAssists,
            'topCleanSheets' => $topCleanSheets,
        ]);
    }
}

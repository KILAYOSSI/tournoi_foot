<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\JoueurRepository;

class StatistiquesController extends AbstractController
{
    /**
     * @Route("/statistiques", name="statistiques")
     */
    public function index(JoueurRepository $joueurRepository): Response
    {
        // Récupérer les meilleurs buteurs
        $meilleursButeurs = $joueurRepository->findTopScorers();

        // Récupérer les meilleurs passeurs
        $meilleursPasseurs = $joueurRepository->findTopAssisters();

        // Récupérer les meilleurs gardiens
        $meilleursGardiens = $joueurRepository->findTopGoalkeepers();

        // Récupérer le meilleur joueur global (buts + passes)
        $meilleurJoueurGlobal = $joueurRepository->findBestOverallPlayers();

        // Tous les joueurs pour le filtre graphique
        $joueurs = $joueurRepository->findAll();

        return $this->render('pages/statistiques_football.html.twig', [
            'meilleursButeurs' => $meilleursButeurs,
            'meilleursPasseurs' => $meilleursPasseurs,
            'meilleursGardiens' => $meilleursGardiens,
            'meilleurJoueurGlobal' => $meilleurJoueurGlobal,
            'joueurs' => $joueurs,
        ]);
    }
}

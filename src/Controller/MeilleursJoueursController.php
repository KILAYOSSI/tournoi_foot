<?php

namespace App\Controller;

use App\Repository\StatistiquesmatchRepository;
use App\Repository\JoueurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MeilleursJoueursController extends AbstractController
{
    /**
     * @Route("/meilleurs-joueurs", name="meilleurs_joueurs")
     */
    public function index(StatistiquesmatchRepository $statistiquesRepository, JoueurRepository $joueurRepository): Response
    {
        // Exemple d'utilisation corrigée avec le bon nom de classe
        $statistiques = $statistiquesRepository->findAll();
        $joueurs = $joueurRepository->findAll();

        // Logique métier ici...

        return $this->render('meilleurs_joueurs/index.html.twig', [
            'statistiques' => $statistiques,
            'joueurs' => $joueurs,
        ]);
    }
}

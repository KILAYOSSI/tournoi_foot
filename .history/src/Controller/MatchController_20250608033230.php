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
        // Récupérer les données nécessaires pour la page match-du-jour
        $matchsDuJour = []; // Remplacer par la récupération réelle des données

        return $this->render('pages/match_du_jour.html.twig', [
            'matchsDuJour' => $matchsDuJour,
        ]);
    }
}

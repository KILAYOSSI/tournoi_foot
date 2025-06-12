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

        // Exemple de données fictives pour éviter l'erreur dans le template
        $matchsDuJour = [
            ['equipe1' => 'Equipe A', 'equipe2' => 'Equipe B', 'score' => '2-1', 'date' => '2025-06-10'],
            ['equipe1' => 'Equipe C', 'equipe2' => 'Equipe D', 'score' => '0-0', 'date' => '2025-06-10'],
        ];

        return $this->render('pages/match_du_jour.html.twig', [
            'matchsDuJour' => $matchsDuJour,
        ]);
    }
}

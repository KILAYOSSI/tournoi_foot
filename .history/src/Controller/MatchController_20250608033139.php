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
        // Implémentation de la méthode matchDuJour
        // Ajoutez ici le code nécessaire pour gérer la page "match-du-jour"

        return $this->render('pages/match_du_jour.html.twig', [
            // variables à passer à la vue
        ]);
    }
}

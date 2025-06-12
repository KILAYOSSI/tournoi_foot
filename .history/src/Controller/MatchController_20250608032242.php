<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MatchController extends AbstractController
{
    /**
     * @Route("/matchs", name="matchs")
     */
    public function index(): Response
    {
        // $classementRepository = $this->getDoctrine()->getRepository(\App\Entity\ClassementPoule::class);

        // Récupérer les données nécessaires pour les statistiques
        // $classementRepository = $this->getDoctrine()->getRepository(\App\Entity\ClassementPoule::class);

        // ... le reste du code inchangé ...

        return $this->render('pages/matchs.html.twig', [
            // 'classements' => $classements,
            // autres variables à passer à la vue
        ]);
    }
}

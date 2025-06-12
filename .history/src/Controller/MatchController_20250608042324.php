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
        return $this->render('pages/match_du_jour_minimal.html.twig');
    }
}

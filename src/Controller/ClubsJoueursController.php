<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClubsJoueursController extends AbstractController
{
    /**
     * @Route("/clubs-joueurs", name="clubs_joueurs")
     */
    public function index(): Response
    {
        $clubRepository = $this->getDoctrine()->getRepository(\App\Entity\Club::class);
        $clubs = $clubRepository->findAll();

        return $this->render('pages/clubs_joueurs.html.twig', [
            'clubs' => $clubs,
        ]);
    }
}

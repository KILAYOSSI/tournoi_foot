<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartonsController extends AbstractController
{
    /**
     * @Route("/cartons", name="cartons")
     */
    public function index(): Response
    {
        $statistiquesRepository = $this->getDoctrine()->getRepository(\App\Entity\Statistiquesmatch::class);
        $cartonsStats = $statistiquesRepository->createQueryBuilder('s')
            // Supprimer d'abord les statistiques liées à la rencontre
            ->getQuery()
            ->getResult();

        // Your logic here

        return $this->render('cartons/index.html.twig', [
            'cartonsStats' => $cartonsStats,
        ]);
    }
}

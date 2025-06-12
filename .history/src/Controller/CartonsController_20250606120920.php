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
        $statistiquesRepository = $this->getDoctrine()->getRepository(\App\Entity\Statistiquematch::class);
        $cartonsStats = $statistiquesRepository->createQueryBuilder('s')
            ->select('IDENTITY(s.joueur) as joueurId, SUM(s.cartonsjaunes) as totalCartonsJaunes, SUM(s.cartonsrouges) as totalCartonsRouges')
            ->groupBy('s.joueur')
            ->orderBy('totalCartonsRouges', 'DESC')
            ->getQuery()
            ->getResult();

        $joueurRepository = $this->getDoctrine()->getRepository(\App\Entity\Joueur::class);
        foreach ($cartonsStats as &$stat) {
            if ($stat['joueurId'] !== null) {
                $joueur = $joueurRepository->find($stat['joueurId']);
                $stat['joueur'] = $joueur !== null ? $joueur : null;
            } else {
                $stat['joueur'] = null;
            }
        }

        return $this->render('pages/cartons.html.twig', [
            'cartonsStats' => $cartonsStats,
        ]);
    }
}

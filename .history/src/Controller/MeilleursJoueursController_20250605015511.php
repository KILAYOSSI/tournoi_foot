<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MeilleursJoueursController extends AbstractController
{
    /**
     * @Route("/meilleurs-joueurs", name="meilleurs_joueurs")
     */
    public function index(): Response
    {
        $statistiquesRepository = $this->getDoctrine()->getRepository(\App\Entity\Statistiquematch::class);
        $joueurRepository = $this->getDoctrine()->getRepository(\App\Entity\Joueur::class);

        // Meilleurs buteurs
        $meilleursButeurs = $statistiquesRepository->createQueryBuilder('s')
            ->select('s, SUM(s.buts) as totalButs')
            ->join('s.joueur', 'j')
            ->addSelect('j as joueur')
            ->groupBy('j.id')
            ->orderBy('totalButs', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

        // Meilleurs passeurs
        $meilleursPasseurs = $statistiquesRepository->createQueryBuilder('s')
            ->select('s, SUM(s.passes) as totalPasses')
            ->join('s.joueur', 'j')
            ->addSelect('j as joueur')
            ->groupBy('j.id')
            ->orderBy('totalPasses', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

        // Meilleurs cleansheets (remplacé par cleanSheets si existant, sinon ignoré)
        // Comme le champ cleansheets n'existe pas, on supprime cette requête
        $meilleursCleansheets = [];

        // Meilleurs G/A (buts + passes)
        $meilleursGA = $statistiquesRepository->createQueryBuilder('s')
            ->select('s, SUM(s.buts + s.passes) as totalGA')
            ->join('s.joueur', 'j')
            ->addSelect('j as joueur')
            ->groupBy('j.id')
            ->orderBy('totalGA', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

        // Récupérer les entités Joueur pour chaque classement
        foreach ([$meilleursButeurs, $meilleursPasseurs, $meilleursCleansheets, $meilleursGA] as &$classement) {
            foreach ($classement as &$stat) {
                // Plus besoin de récupérer le joueur, il est déjà dans 'joueur'
                if (!isset($stat['joueur'])) {
                    $stat['joueur'] = null;
                }
            }
        }

        return $this->render('pages/meilleurs_joueurs.html.twig', [
            'meilleursButeurs' => $meilleursButeurs,
            'meilleursPasseurs' => $meilleursPasseurs,
            'meilleursCleansheets' => $meilleursCleansheets,
            'meilleursGA' => $meilleursGA,
        ]);
    }
}

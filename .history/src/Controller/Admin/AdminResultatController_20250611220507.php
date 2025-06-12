<?php

namespace App\Controller\Admin;

use App\Entity\Statistiquesmatch;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminResultatController extends AbstractController
{
    /**
     * @Route("/admin/resultat", name="admin_resultat")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Remplace cette ligne par la logique pour récupérer le résultat courant
        // Par exemple, tu peux récupérer un résultat par son ID ou autre critère
        // $resultat = $entityManager->getRepository(Resultat::class)->find($id);
        $resultat = null; // À remplacer par ta logique

        $statistiques = [];
        if ($resultat !== null) {
            $statistiques = $entityManager->getRepository(Statistiquesmatch::class)->findBy(['rencontre' => $resultat]);
            foreach ($statistiques as $stat) {
                // Supprimer d'abord les statistiques liées à la rencontre
                // $entityManager->remove($stat);
            }
            // $entityManager->flush();
        }

        // Autres traitements ici

        return $this->render('admin/resultats/index.html.twig', [
            'resultat' => $resultat,
            'statistiques' => $statistiques,
        ]);
    }
}
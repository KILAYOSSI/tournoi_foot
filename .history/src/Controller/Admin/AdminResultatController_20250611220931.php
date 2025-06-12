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
     * @Route("/admin-panel/resultats", name="admin_resultats")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupérer tous les résultats
        $resultats = $entityManager->getRepository('App\Entity\Match')->findAll();

        return $this->render('admin/resultats/index.html.twig', [
            'resultats' => $resultats,
        ]);
    }

    /**
     * @Route("/admin-panel/resultats/{id}/edit", name="admin_resultats_edit")
     */
    public function edit(int $id, EntityManagerInterface $entityManager): Response
    {
        // Récupérer le match par son ID
        $match = $entityManager->getRepository('App\Entity\Match')->find($id);

        if (!$match) {
            throw $this->createNotFoundException('Match non trouvé avec l\'ID ' . $id);
        }

        // TODO: Implémenter la logique d'édition réelle ici (formulaire, validation, sauvegarde)

        // Pour l'instant, on retourne une réponse simple pour indiquer la page d'édition
        return new Response("Page d'édition du résultat pour le match ID " . $id);
    }
}

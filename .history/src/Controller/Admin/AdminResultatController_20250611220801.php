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
    public function edit(int $id): Response
    {
        // Logique pour éditer un résultat par son ID
        // À implémenter selon les besoins
        return new Response("Page d'édition du résultat avec l'ID $id");
    }
}

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
        // Récupérer tous les résultats
        $resultats = $entityManager->getRepository('App\Entity\Resultat')->findAll();

        return $this->render('admin/resultats/index.html.twig', [
            'resultats' => $resultats,
        ]);
    }
}
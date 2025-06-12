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
        $resultat = ...; // Your logic to get the current result

        $statistiques = $entityManager->getRepository(Statistiquesmatch::class)->findBy(['rencontre' => $resultat]);
        foreach ($statistiques as $stat) {
            // Supprimer d'abord les statistiques liées à la rencontre
            // Your logic here
        }

        // Your other logic here

        return $this->render('admin/resultat/index.html.twig', [
            // Your template variables here
        ]);
    }
}

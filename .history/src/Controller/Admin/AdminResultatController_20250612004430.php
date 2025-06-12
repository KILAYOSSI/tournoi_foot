<?php

namespace App\Controller\Admin;

use App\Entity\Match;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\MatchType;

class AdminResultatController extends AbstractController
{
    /**
     * @Route("/admin-panel/resultats", name="admin_resultats")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupérer tous les résultats
        $resultats = $entityManager->getRepository(Match::class)->findAll();

        return $this->render('admin/resultats/index.html.twig', [
            'resultats' => $resultats,
        ]);
    }

    /**
     * @Route("/admin-panel/resultats/new", name="admin_resultats_new")
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $match = new Match();
        $form = $this->createForm(MatchType::class, $match);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($match);
            $entityManager->flush();

            return $this->redirectToRoute('admin_resultats');
        }

        return $this->render('admin/resultats/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin-panel/resultats/{id}/edit", name="admin_resultats_edit")
     */
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer le match par son ID
        $match = $entityManager->getRepository(Match::class)->find($id);

        if (!$match) {
            throw $this->createNotFoundException('Match non trouvé avec l\'ID ' . $id);
        }

        // Créer le formulaire d'édition
        $form = $this->createForm(MatchType::class, $match);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            // Rediriger vers la liste des résultats après la sauvegarde
            return $this->redirectToRoute('admin_resultats');
        }

        // Rendre le formulaire dans un template
        return $this->render('admin/resultats/edit.html.twig', [
            'form' => $form->createView(),
            'match' => $match,
        ]);
    }

    /**
     * @Route("/admin-panel/resultats/{id}/delete", name="admin_resultats_delete", methods={"POST"})
     */
    public function delete(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $match = $entityManager->getRepository(Match::class)->find($id);

        if (!$match) {
            throw $this->createNotFoundException('Match non trouvé avec l\'ID ' . $id);
        }

        if ($this->isCsrfTokenValid('delete'.$match->getId(), $request->request->get('_token'))) {
            $entityManager->remove($match);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_resultats');
    }
}
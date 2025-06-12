<?php

namespace App\Controller\Admin;

use App\Entity\Rencontre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminResultatController extends AbstractController
{
    /**
     * @Route("/admin-panel/resultats", name="admin_resultats_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $resultats = $entityManager->getRepository(Rencontre::class)->findAll();

        return $this->render('admin/resultats/index.html.twig', [
            'resultats' => $resultats,
        ]);
    }

    /**
     * @Route("/admin-panel/resultats/new", name="admin_resultats_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $resultat = new Rencontre();
        $form = $this->createForm(\App\Form\RencontreType::class, $resultat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($resultat);
            $entityManager->flush();

            $this->addFlash('success', 'Résultat créé avec succès.');

            return $this->redirectToRoute('admin_resultats_index');
        }

        return $this->render('admin/resultats/new.html.twig', [
            'resultat' => $resultat,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin-panel/resultats/{id}/delete", name="admin_resultat_delete", methods={"POST"})
     */
    public function delete(Request $request, Rencontre $resultat, EntityManagerInterface $entityManager): RedirectResponse
    {
        if ($this->isCsrfTokenValid('delete'.$resultat->getId(), $request->request->get('_token'))) {
            // Supprimer d'abord les statistiques liées à la rencontre
            $statistiques = $entityManager->getRepository(\App\Entity\Statistiquematch::class)->findBy(['rencontre' => $resultat]);
            foreach ($statistiques as $stat) {
                $entityManager->remove($stat);
            }
            // Puis supprimer la rencontre
            $entityManager->remove($resultat);
            $entityManager->flush();

            $this->addFlash('success', 'Résultat supprimé avec succès.');
        }

        return $this->redirectToRoute('admin_resultats_index');
    }

    /**
     * @Route("/admin-panel/resultats/{id}/edit", name="admin_resultats_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Rencontre $resultat, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(\App\Form\RencontreType::class, $resultat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Résultat modifié avec succès.');

            return $this->redirectToRoute('admin_resultats_index');
        }

        return $this->render('admin/resultats/edit.html.twig', [
            'resultat' => $resultat,
            'form' => $form->createView(),
        ]);
    }
}

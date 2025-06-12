<?php

namespace App\Controller\Admin;

use App\Entity\Rencontre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\RencontreType;

class AdminResultatController extends AbstractController
{
    /**
     * @Route("/admin-panel/resultats", name="admin_resultats")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupérer toutes les rencontres
        $rencontres = $entityManager->getRepository(Rencontre::class)->findAll();

        return $this->render('admin/resultats/index.html.twig', [
            'rencontres' => $rencontres,
        ]);
    }

    /**
     * @Route("/admin-panel/resultats/new", name="admin_resultats_new")
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $rencontre = new Rencontre();
        $form = $this->createForm(RencontreType::class, $rencontre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($rencontre);
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
        $rencontre = $entityManager->getRepository(Rencontre::class)->find($id);

        if (!$rencontre) {
            throw $this->createNotFoundException('Rencontre non trouvée avec l\'ID ' . $id);
        }

        $form = $this->createForm(RencontreType::class, $rencontre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('admin_resultats');
        }

        return $this->render('admin/resultats/edit.html.twig', [
            'form' => $form->createView(),
            'rencontre' => $rencontre,
        ]);
    }

    /**
     * @Route("/admin-panel/resultats/{id}/delete", name="admin_resultats_delete", methods={"POST"})
     */
    public function delete(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $rencontre = $entityManager->getRepository(Rencontre::class)->find($id);

        if (!$rencontre) {
            throw $this->createNotFoundException('Rencontre non trouvée avec l\'ID ' . $id);
        }

        if ($this->isCsrfTokenValid('delete'.$rencontre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($rencontre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_resultats');
    }
}
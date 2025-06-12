<?php

namespace App\Controller\Admin;

use App\Entity\Joueur;
use App\Form\JoueurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminJoueurController extends AbstractController
{
    /**
     * @Route("/admin-panel/joueurs", name="admin_joueurs")
     */
    public function index(EntityManagerInterface $em): Response
    {
        $joueurs = $em->getRepository(Joueur::class)->findAll();

        return $this->render('admin/joueurs/index.html.twig', [
            'joueurs' => $joueurs,
        ]);
    }

    /**
     * @Route("/admin-panel/joueurs/new", name="admin_joueurs_new")
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $joueur = new Joueur();
        $form = $this->createForm(JoueurType::class, $joueur);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($joueur);
            $em->flush();

            return $this->redirectToRoute('admin_joueurs');
        }

        return $this->render('admin/joueurs/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin-panel/joueurs/{id}/edit", name="admin_joueurs_edit")
     */
    public function edit(Joueur $joueur, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(JoueurType::class, $joueur);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('admin_joueurs');
        }

        return $this->render('admin/joueurs/edit.html.twig', [
            'form' => $form->createView(),
            'joueur' => $joueur,
        ]);
    }

    /**
     * @Route("/admin-panel/joueurs/{id}/delete", name="admin_joueurs_delete", methods={"POST"})
     */
    public function delete(Joueur $joueur, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$joueur->getId(), $request->request->get('_token'))) {
            $em->remove($joueur);
            $em->flush();
        }

        return $this->redirectToRoute('admin_joueurs');
    }
}

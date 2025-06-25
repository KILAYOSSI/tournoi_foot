<?php

namespace App\Controller\Admin;

use App\Entity\Rencontre;
use App\Form\RencontreType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminRencontreController extends AbstractController
{
    /**
     * @Route("/admin-panel/rencontres", name="admin_rencontres")
     */
    public function index(EntityManagerInterface $em): Response
    {
        $rencontres = $em->getRepository(Rencontre::class)->findAll();

        return $this->render('admin/resultats/index.html.twig', [
            'rencontres' => $rencontres,
        ]);
    }

    /**
     * @Route("/admin-panel/rencontres/new", name="admin_rencontres_new")
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $rencontre = new Rencontre();
        $form = $this->createForm(RencontreType::class, $rencontre);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($rencontre);
            $em->flush();

            return $this->redirectToRoute('admin_rencontres');
        }

        return $this->render('admin/rencontres/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin-panel/rencontres/{id}/edit", name="admin_rencontres_edit")
     */
    public function edit(Rencontre $rencontre, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(RencontreType::class, $rencontre);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('admin_rencontres');
        }

        return $this->render('admin/rencontres/edit.html.twig', [
            'form' => $form->createView(),
            'rencontre' => $rencontre,
        ]);
    }

    /**
     * @Route("/admin-panel/rencontres/{id}/delete", name="admin_rencontres_delete", methods={"POST"})
     */
    public function delete(Rencontre $rencontre, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rencontre->getId(), $request->request->get('_token'))) {
            $statistiques = $em->getRepository(\App\Entity\Statistiquesmatch::class)->findBy(['rencontre' => $rencontre]);
            foreach ($statistiques as $stat) {
                $em->remove($stat);
            }
            $em->remove($rencontre);
            $em->flush();
        }

        return $this->redirectToRoute('admin_rencontres');
    }
}
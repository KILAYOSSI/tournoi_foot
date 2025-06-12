<?php

namespace App\Controller\Admin;

use App\Entity\Club;
use App\Form\ClubType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminClubController extends AbstractController
{
    /**
     * @Route("/admin-panel/clubs", name="admin_clubs")
     */
    public function index(EntityManagerInterface $em): Response
    {
        $clubs = $em->getRepository(Club::class)->findAll();

        return $this->render('admin/clubs/index.html.twig', [
            'clubs' => $clubs,
        ]);
    }

    /**
     * @Route("/admin-panel/clubs/new", name="admin_clubs_new")
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $club = new Club();
        $form = $this->createForm(ClubType::class, $club);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($club);
            $em->flush();

            return $this->redirectToRoute('admin_clubs');
        }

        return $this->render('admin/clubs/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin-panel/clubs/{id}/edit", name="admin_clubs_edit")
     */
    public function edit(Club $club, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ClubType::class, $club);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('admin_clubs');
        }

        return $this->render('admin/clubs/edit.html.twig', [
            'form' => $form->createView(),
            'club' => $club,
        ]);
    }

    /**
     * @Route("/admin-panel/clubs/{id}/delete", name="admin_clubs_delete", methods={"POST"})
     */
    public function delete(Club $club, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$club->getId(), $request->request->get('_token'))) {
            $em->remove($club);
            $em->flush();
        }

        return $this->redirectToRoute('admin_clubs');
    }
}

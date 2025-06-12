<?php

namespace App\Controller\Admin;

use App\Entity\Carton;
use App\Form\CartonType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCartonController extends AbstractController
{
    /**
     * @Route("/admin-panel/cartons", name="admin_cartons")
     */
    public function index(EntityManagerInterface $em): Response
    {
        $cartons = $em->getRepository(Carton::class)->findAll();

        return $this->render('admin/cartons/index.html.twig', [
            'cartons' => $cartons,
        ]);
    }

    /**
     * @Route("/admin-panel/cartons/new", name="admin_cartons_new")
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $carton = new Carton();
        $form = $this->createForm(CartonType::class, $carton);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($carton);
            $em->flush();

            return $this->redirectToRoute('admin_cartons');
        }

        return $this->render('admin/cartons/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin-panel/cartons/{id}/edit", name="admin_cartons_edit")
     */
    public function edit(Carton $carton, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CartonType::class, $carton);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('admin_cartons');
        }

        return $this->render('admin/cartons/edit.html.twig', [
            'form' => $form->createView(),
            'carton' => $carton,
        ]);
    }

    /**
     * @Route("/admin-panel/cartons/{id}/delete", name="admin_cartons_delete", methods={"POST"})
     */
    public function delete(Carton $carton, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$carton->getId(), $request->request->get('_token'))) {
            $em->remove($carton);
            $em->flush();
        }

        return $this->redirectToRoute('admin_cartons');
    }
}

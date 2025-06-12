<?php

namespace App\Controller\Admin;

use App\Entity\Match as MatchEntity;
use App\Form\MatchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminMatchController extends AbstractController
{
    /**
     * @Route("/admin-panel/matchs", name="admin_matchs")
     */
    public function index(EntityManagerInterface $em): Response
    {
        $matchs = $em->getRepository(MatchEntity::class)->findAll();

        return $this->render('admin/matchs/index.html.twig', [
            'matchs' => $matchs,
        ]);
    }

    /**
     * @Route("/admin-panel/matchs/new", name="admin_matchs_new")
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $match = new MatchEntity();
        $form = $this->createForm(MatchType::class, $match);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($match);
            $em->flush();

            return $this->redirectToRoute('admin_matchs');
        }

        return $this->render('admin/matchs/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin-panel/matchs/{id}/edit", name="admin_matchs_edit")
     */
    public function edit(MatchEntity $match, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(MatchType::class, $match);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('admin_matchs');
        }

        return $this->render('admin/matchs/edit.html.twig', [
            'form' => $form->createView(),
            'match' => $match,
        ]);
    }

    /**
     * @Route("/admin-panel/matchs/{id}/delete", name="admin_matchs_delete", methods={"POST"})
     */
    public function delete(MatchEntity $match, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$match->getId(), $request->request->get('_token'))) {
            $em->remove($match);
            $em->flush();
        }

        return $this->redirectToRoute('admin_matchs');
    }
}

<?php

namespace App\Controller\Admin;

use App\Repository\StatistiquesmatchRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Statistiquematch;
use App\Form\StatistiquematchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class AdminStatistiqueController extends AbstractController
{
    /**
     * @Route("/admin-panel/statistiques", name="admin_statistiques_index")
     */
    public function index(StatistiquematchRepository $statistiqueRepository): Response
    {
        $statistiques = $statistiqueRepository->findAll();

        return $this->render('admin/statistiques/index.html.twig', [
            'statistiques' => $statistiques,
        ]);
    }

    /**
     * @Route("/admin-panel/statistiques/new", name="admin_statistiques_new")
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $statistique = new Statistiquematch();
        $form = $this->createForm(StatistiquematchType::class, $statistique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($statistique);
            $entityManager->flush();

            return $this->redirectToRoute('admin_statistiques_index');
        }

        return $this->render('admin/statistiques/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin-panel/statistiques/{id}/edit", name="admin_statistiques_edit")
     */
    public function edit(Request $request, EntityManagerInterface $entityManager, ManagerRegistry $doctrine, $id): Response
    {
        $statistique = $doctrine->getRepository(Statistiquematch::class)->find($id);

        if (!$statistique) {
            throw $this->createNotFoundException('Statistique non trouvée pour id '.$id);
        }

        $form = $this->createForm(StatistiquematchType::class, $statistique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_statistiques_index');
        }

        return $this->render('admin/statistiques/edit.html.twig', [
            'form' => $form->createView(),
            'statistique' => $statistique,
        ]);
    }

    /**
     * @Route("/admin-panel/statistiques/{id}/delete", name="admin_statistiques_delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, ManagerRegistry $doctrine, $id): Response
    {
        $statistique = $doctrine->getRepository(Statistiquematch::class)->find($id);

        if (!$statistique) {
            throw $this->createNotFoundException('Statistique non trouvée pour id '.$id);
        }

        if ($this->isCsrfTokenValid('delete'.$statistique->getId(), $request->request->get('_token'))) {
            $entityManager->remove($statistique);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_statistiques_index');
    }
}

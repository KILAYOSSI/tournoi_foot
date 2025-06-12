<?php

namespace App\Controller;

use App\Repository\ClubRepository;
use App\Repository\MatchRepository;
use App\Repository\StatistiquematchRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ClubRepository $clubRepository, MatchRepository $matchRepository, StatistiquematchRepository $statistiqueRepository): Response
    {
        $clubsCount = $clubRepository->count([]);
        $matchsCount = $matchRepository->count([]);
        $butsCount = 0;
        $statistiquesCount = $statistiqueRepository->count([]);

        $allStats = $statistiqueRepository->findAll();
        foreach ($allStats as $stat) {
            $butsCount += $stat->getButs();
        }

        return $this->render('pages/home.html.twig', [
            'clubsCount' => $clubsCount,
            'matchsCount' => $matchsCount,
            'butsCount' => $butsCount,
            'statistiquesCount' => $statistiquesCount,
        ]);
    }

    /**
     * @Route("/about", name="about")
     */
    public function about(): Response
    {
        return $this->render('pages/about.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(): Response
    {
        return $this->render('pages/contact.html.twig');
    }
}

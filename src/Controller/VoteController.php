<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VoteController extends AbstractController
{
    /**
     * @Route("/vote", name="vote")
     */
    public function index(): Response
    {
        $voteRepository = $this->getDoctrine()->getRepository(\App\Entity\Vote::class);
        $votesStats = $voteRepository->createQueryBuilder('v')
            ->select('IDENTITY(v.club) as clubId, COUNT(v.id) as totalVotes')
            ->groupBy('v.club')
            ->orderBy('totalVotes', 'DESC')
            ->getQuery()
            ->getResult();

        $clubRepository = $this->getDoctrine()->getRepository(\App\Entity\Club::class);
        foreach ($votesStats as &$stat) {
            $stat['club'] = $clubRepository->find($stat['clubId']);
        }

        return $this->render('pages/vote.html.twig', [
            'votesStats' => $votesStats,
        ]);
    }
}

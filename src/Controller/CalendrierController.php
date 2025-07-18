<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Dompdf\Dompdf;
use Dompdf\Options;

class CalendrierController extends AbstractController
{
    /**
     * @Route("/calendrier", name="calendrier")
     */
    public function index(): Response
    {
        $matchRepository = $this->getDoctrine()->getRepository(\App\Entity\Match::class);
        $rencontreRepository = $this->getDoctrine()->getRepository(\App\Entity\Rencontre::class);

        $timezone = new \DateTimeZone('Europe/Paris');
        $aujourdhui = new \DateTime('now', $timezone);

        // Début de la journée (00:00:00)
        $debutJournee = (clone $aujourdhui)->setTime(0, 0, 0);
        // Fin de la journée (23:59:59)
        $finJournee = (clone $aujourdhui)->setTime(23, 59, 59);

        $matchsAVenir = $matchRepository->createQueryBuilder('m')
            ->where('m.date >= :debutJournee')
            ->setParameter('debutJournee', $debutJournee)
            ->orderBy('m.date', 'ASC')
            ->getQuery()
            ->getResult();

        $matchsResultats = $rencontreRepository->createQueryBuilder('r')
            ->where('r.joue = :joue')
            ->andWhere('r.dateHeure BETWEEN :debutJournee AND :finJournee')
            ->setParameter('joue', true)
            ->setParameter('debutJournee', $debutJournee)
            ->setParameter('finJournee', $finJournee)
            ->orderBy('r.dateHeure', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('pages/calendrier.html.twig', [
            'matchsAVenir' => $matchsAVenir,
            'matchsResultats' => $matchsResultats,
        ]);
    }

    /**
     * @Route("/calendrier/export/pdf", name="calendrier_export_pdf")
     */
    public function exportPdf(): Response
    {
        $matchRepository = $this->getDoctrine()->getRepository(\App\Entity\Match::class);
        $rencontreRepository = $this->getDoctrine()->getRepository(\App\Entity\Rencontre::class);

        $timezone = new \DateTimeZone('Europe/Paris');
        $aujourdhui = new \DateTime('now', $timezone);

        $debutJournee = (clone $aujourdhui)->setTime(0, 0, 0);
        $finJournee = (clone $aujourdhui)->setTime(23, 59, 59);

        $matchsAVenir = $matchRepository->createQueryBuilder('m')
            ->where('m.date >= :debutJournee')
            ->setParameter('debutJournee', $debutJournee)
            ->orderBy('m.date', 'ASC')
            ->getQuery()
            ->getResult();

        $matchsResultats = $rencontreRepository->createQueryBuilder('r')
            ->where('r.joue = :joue')
            ->andWhere('r.dateHeure BETWEEN :debutJournee AND :finJournee')
            ->setParameter('joue', true)
            ->setParameter('debutJournee', $debutJournee)
            ->setParameter('finJournee', $finJournee)
            ->orderBy('r.dateHeure', 'DESC')
            ->getQuery()
            ->getResult();

        $html = $this->renderView('pages/calendrier_pdf.html.twig', [
            'matchsAVenir' => $matchsAVenir,
            'matchsResultats' => $matchsResultats,
        ]);

        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $pdfOutput = $dompdf->output();

        return new Response($pdfOutput, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="calendrier_matchs_resultats.pdf"',
        ]);
    }
}

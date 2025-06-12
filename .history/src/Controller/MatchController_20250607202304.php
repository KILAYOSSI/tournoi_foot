<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PouleRepository;

class MatchController extends AbstractController
{
    private $pouleRepository;
    private $updateClassementService;
    private $entityManager;

    public function __construct(PouleRepository $pouleRepository, \App\Service\UpdateClassementService $updateClassementService, \Doctrine\ORM\EntityManagerInterface $entityManager)
    {
        $this->pouleRepository = $pouleRepository;
        $this->updateClassementService = $updateClassementService;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/match-du-jour", name="match_du_jour")
     */
    public function matchDuJour(): Response
    {
        $matchRepository = $this->getDoctrine()->getRepository(\App\Entity\Match::class);
        $rencontreRepository = $this->getDoctrine()->getRepository(\App\Entity\Rencontre::class);
        $classementRepository = $this->getDoctrine()->getRepository(\App\Entity\ClassementPoule::class);

        $matchsDuJour = $matchRepository->findMatchsDuJour();

        $timezone = new \DateTimeZone('Europe/Paris');
        $today = new \DateTime('now', $timezone);
        $startOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', $today->format('Y-m-d') . ' 00:00:00', $timezone);
        $endOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', $today->format('Y-m-d') . ' 23:59:59', $timezone);

        $matchsResultats = $rencontreRepository->createQueryBuilder('r')
            ->where('r.dateHeure >= :startOfDay')
            ->andWhere('r.dateHeure <= :endOfDay')
            ->setParameter('startOfDay', $startOfDay)
            ->setParameter('endOfDay', $endOfDay)
            ->orderBy('r.dateHeure', 'DESC')
            ->getQuery()
            ->getResult();

        // Récupérer tous les classements
        $classements = $classementRepository->findAll();

        // Regrouper par poule
        $poules = ['A' => [], 'B' => [], 'C' => [], 'D' => []];
        foreach ($classements as $classement) {
            $poule = $classement->getPoule();
            $pouleNom = $poule->getNom();
            $pouleId = $poule->getId();

            // Remapper les poules 19 et 20 en 'C' et 'D'
            if ($pouleId === 19) {
                $key = 'C';
            } elseif ($pouleId === 20) {
                $key = 'D';
            } elseif (in_array($pouleNom, ['Poule A', 'Poule B', 'Poule C', 'Poule D'])) {
                $key = $pouleNom[strlen($pouleNom) - 1];
            } else {
                continue; // Ignorer les autres poules
            }

            // Calculer les points automatiquement
            $points = $classement->getGagnes() * 3 + $classement->getNuls();
            $classement->setPoints($points);

            // Initialiser cartons à 0 si null
            if ($classement->getCartonsJaunes() === null) {
                $classement->setCartonsJaunes(0);
            }
            if ($classement->getCartonsRouges() === null) {
                $classement->setCartonsRouges(0);
            }

            $poules[$key][] = $classement;
        }

        // Trier chaque poule selon les critères : points, goalaverage, butsPour
        foreach ($poules as $key => &$classementsPoule) {
            usort($classementsPoule, function($a, $b) {
                if ($b->getPoints() !== $a->getPoints()) {
                    return $b->getPoints() - $a->getPoints();
                }
                if ($b->getGoalaverage() !== $a->getGoalaverage()) {
                    return $b->getGoalaverage() - $a->getGoalaverage();
                }
                return $b->getButsPour() - $a->getButsPour();
            });

            // Mettre à jour le rang
            $rang = 1;
            foreach ($classementsPoule as $classement) {
                $classement->setRang($rang++);
            }
        }

        return $this->render('pages/match_du_jour.html.twig', [
            'matchsDuJour' => $matchsDuJour,
            'matchsResultats' => $matchsResultats,
            'poules' => $poules,
        ]);
    }
}

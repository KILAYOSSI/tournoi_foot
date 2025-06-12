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

        $classementRepository = $this->getDoctrine()->getRepository(\App\Entity\ClassementPoule::class);

        // Récupérer explicitement les 4 poules A, B, C, D
        $pouleA = $this->pouleRepository->findOneBy(['nom' => 'Poule A']);
        $pouleB = $this->pouleRepository->findOneBy(['nom' => 'Poule B']);
        $pouleC = $this->pouleRepository->findOneBy(['nom' => 'Poule C']);
        $pouleD = $this->pouleRepository->findOneBy(['nom' => 'Poule D']);

        $poulesFiltres = array_filter([$pouleA, $pouleB, $pouleC, $pouleD], function($poule) {
            return $poule !== null;
        });

        // Appeler le service UpdateClassementService pour mettre à jour les classements
        $this->updateClassementService->updateClassements();

        // Recharger les classements après mise à jour
        $classements = [];
        $matchsParPoule = [];

        foreach ($poulesFiltres as $poule) {
            // Rafraîchir la poule et ses clubs
            $this->entityManager->refresh($poule);
            foreach ($poule->getClubs() as $club) {
                $this->entityManager->refresh($club);
            }

            // Recharger les classements à jour
            $qbClassement = $classementRepository->createQueryBuilder('c')
                ->where('c.poule = :pouleId')
                ->andWhere('c.club IS NOT NULL')
                ->setParameter('pouleId', $poule->getId())
                ->orderBy('c.rang', 'ASC'); // Trier par rang pour correspondre à l'ordre

            $classementResult = $qbClassement->getQuery()->getResult();

            // Log du nombre de résultats pour debug
            $logFile = __DIR__ . '/../../var/log/classement_debug.log';
            if (!file_exists($logFile)) {
                file_put_contents($logFile, ""); // Créer le fichier s'il n'existe pas
            }
            file_put_contents($logFile, "Nombre de classements pour poule " . $poule->getNom() . " : " . count($classementResult) . "\n", FILE_APPEND);

            // Log IDs des clubs dans le classement
            file_put_contents($logFile, "Classement poule " . $poule->getNom() . " :\n", FILE_APPEND);
            foreach ($classementResult as $c) {
                file_put_contents($logFile, "Club ID: " . $c->getClub()->getId() . ", Rang: " . $c->getRang() . ", Points: " . $c->getPoints() . "\n", FILE_APPEND);
            }

            // Vérification clé poule.nom
            if (!isset($classements[$poule->getNom()])) {
                file_put_contents($logFile, "Clé poule.nom absente dans classements pour : " . $poule->getNom() . "\n", FILE_APPEND);
            }

            $classements[$poule->getNom()] = $classementResult;

            // Récupérer les matchs pour cette poule
            $qbMatchs = $matchRepository->createQueryBuilder('m')
                ->join('m.club1', 'club1')
                ->join('m.club2', 'club2')
                ->join('club1.poule', 'poule1')
                ->where('poule1.id = :pouleId')
                ->setParameter('pouleId', $poule->getId())
                ->orderBy('m.date', 'ASC');

            $matchsParPoule[$poule->getNom()] = $qbMatchs->getQuery()->getResult();
        }

        $statistiquesRepository = $this->getDoctrine()->getRepository(\App\Entity\Statistiquematch::class);
        $cartonsData = $statistiquesRepository->createQueryBuilder('s')
            ->select('IDENTITY(s.joueur) as joueurId, SUM(s.cartonsjaunes) as totalCartonsJaunes, SUM(s.cartonsrouges) as totalCartonsRouges')
            ->groupBy('s.joueur')
            ->getQuery()
            ->getResult();

        // Charger les entités Joueur correspondantes pour les cartons
        $joueurRepository = $this->getDoctrine()->getRepository(\App\Entity\Joueur::class);
        foreach ($cartonsData as &$stat) {
            if ($stat['joueurId'] !== null) {
                $stat['joueur'] = $joueurRepository->find($stat['joueurId']);
            } else {
                $stat['joueur'] = null;
            }
        }

        return $this->render('pages/match_du_jour.html.twig', [
            'matchsDuJour' => $matchsDuJour,
            'matchsResultats' => $matchsResultats,
            'classements' => $classements,
            'matchsParPoule' => $matchsParPoule,
            'cartonsData' => $cartonsData,
            'poules' => $poulesFiltres,
        ]);
    }
}

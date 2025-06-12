<?php

namespace App\Controller;

use App\Repository\ClassementPouleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClassementPouleController extends AbstractController
{
    /**
     * @Route("/classements", name="classements_par_poule")
     */
    public function index(ClassementPouleRepository $classementPouleRepository): Response
    {
        // Récupérer tous les classements
        $classements = $classementPouleRepository->findAll();

        // Regrouper par poule
        $poules = ['A' => [], 'B' => [], 'C' => [], 'D' => []];
        foreach ($classements as $classement) {
            $pouleNom = $classement->getPoule()->getNom();
            if (in_array($pouleNom, ['Poule A', 'Poule B', 'Poule C', 'Poule D'])) {
                // Calculer les points automatiquement
                $points = $classement->getGagnes() * 3 + $classement->getNuls();

                // Mettre à jour les points dans l'entité (optionnel, si on veut garder à jour)
                $classement->setPoints($points);

                $poules[$pouleNom[strlen($pouleNom) - 1]][] = $classement;
            }
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

        return $this->render('pages/classements_par_poule.html.twig', [
            'poules' => $poules,
        ]);
    }
}

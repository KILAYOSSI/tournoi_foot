<?php

namespace App\Controller;

use App\Service\ClassementPoule;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Connection;

class StatistiquesController extends AbstractController
{
    /**
     * @Route("/statistiques", name="statistiques")
     */
    public function index(Connection $connection, ClassementPoule $classementPoule): Response
    {
        // Requête pour les meilleurs buteurs
        $meilleursButeurs = $connection->fetchAllAssociative('
            SELECT j.nom, c.nom AS club, SUM(s.buts) AS buts, COUNT(s.id) AS matchs
            FROM statistiquematch s
            JOIN joueur j ON s.joueur_id = j.id
            JOIN club c ON j.club_id = c.id
            GROUP BY j.id, j.nom, c.nom
            ORDER BY buts DESC
            LIMIT 10
        ');

        // Requête pour les meilleurs passeurs
        $meilleursPasseurs = $connection->fetchAllAssociative('
            SELECT j.nom, c.nom AS club, SUM(s.passes) AS passes, COUNT(s.id) AS matchs
            FROM statistiquematch s
            JOIN joueur j ON s.joueur_id = j.id
            JOIN club c ON j.club_id = c.id
            GROUP BY j.id, j.nom, c.nom
            ORDER BY passes DESC
            LIMIT 10
        ');

        // Requête pour les meilleurs gardiens (champ 'arrets' supprimé car inexistant)
        $meilleursGardiens = $connection->fetchAllAssociative('
            SELECT j.nom, c.nom AS club, SUM(s.cleansheet) AS cleanSheets
            FROM statistiquematch s
            JOIN joueur j ON s.joueur_id = j.id
            JOIN club c ON j.club_id = c.id
            GROUP BY j.id, j.nom, c.nom
            ORDER BY cleanSheets DESC
            LIMIT 10
        ');

        // Requête pour le meilleur joueur global (buts + passes)
        $meilleurJoueurGlobal = $connection->fetchAllAssociative('
            SELECT j.nom, c.nom AS club, SUM(s.buts + s.passes) AS goalsAssists
            FROM statistiquematch s
            JOIN joueur j ON s.joueur_id = j.id
            JOIN club c ON j.club_id = c.id
            GROUP BY j.id, j.nom, c.nom
            ORDER BY goalsAssists DESC
            LIMIT 10
        ');

        // Récupérer tous les joueurs pour le filtre graphique
        $joueurs = $connection->fetchAllAssociative('SELECT id, nom FROM joueur ORDER BY nom');

        // Utilisation du service ClassementPoule si nécessaire
        // $classement = $classementPoule->someMethod();

        return $this->render('pages/statistiques_football.html.twig', [
            'meilleursButeurs' => $meilleursButeurs,
            'meilleursPasseurs' => $meilleursPasseurs,
            'meilleursGardiens' => $meilleursGardiens,
            'meilleurJoueurGlobal' => $meilleurJoueurGlobal,
            'joueurs' => $joueurs,
        ]);
    }
}

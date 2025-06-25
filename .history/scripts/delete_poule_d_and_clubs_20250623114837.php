<?php
// Script PHP pour supprimer la poule D et tous les clubs qui y sont associés, en évitant de casser le code

use App\Entity\Poule;
use App\Entity\Club;
use App\Entity\Joueur;
use App\Entity\Rencontre;
use App\Entity\Vote;
use Symfony\Component\Dotenv\Dotenv;
use App\Kernel;

require_once __DIR__ . '/../vendor/autoload.php';

// Charger les variables d'environnement
if (file_exists(__DIR__ . '/../.env')) {
    (new Dotenv())->load(__DIR__ . '/../.env');
}

// Initialiser le kernel Symfony
$kernel = new Kernel($_SERVER['APP_ENV'] ?? 'dev', (bool) ($_SERVER['APP_DEBUG'] ?? true));
$kernel->boot();

// Récupérer le conteneur de services
$container = $kernel->getContainer();

// Récupérer l'EntityManager
$entityManager = $container->get('doctrine')->getManager();

// Récupérer la poule D
$pouleRepo = $entityManager->getRepository(Poule::class);
$pouleD = $pouleRepo->findOneBy(['nom' => 'Poule D']);

if (!$pouleD) {
    echo "La poule D n'existe pas en base.\n";
    exit(0);
}

// Récupérer les clubs associés à la poule D
$clubRepo = $entityManager->getRepository(Club::class);
$clubs = $clubRepo->findBy(['poule' => $pouleD]);

if (count($clubs) === 0) {
    echo "Aucun club associé à la poule D.\n";
} else {
    // Supprimer les entités liées aux clubs (joueurs, rencontres, votes) avant de supprimer les clubs
    foreach ($clubs as $club) {
        // Supprimer les joueurs du club
        foreach ($club->getJoueurs() as $joueur) {
            $entityManager->remove($joueur);
        }

        // Supprimer les rencontres où le club est club1
        foreach ($club->getRencontresClub1() as $rencontre) {
            $entityManager->remove($rencontre);
        }

        // Supprimer les rencontres où le club est club2
        foreach ($club->getRencontresClub2() as $rencontre) {
            $entityManager->remove($rencontre);
        }

        // Supprimer les votes liés au club
        foreach ($club->getVotes() as $vote) {
            $entityManager->remove($vote);
        }

        // Supprimer le club lui-même
        $entityManager->remove($club);
    }
}

// Supprimer la poule D
$entityManager->remove($pouleD);

// Valider les suppressions en base
$entityManager->flush();

echo "La poule D et tous les clubs associés ont été supprimés avec succès.\n";

<?php
// Script PHP pour mettre à jour les clubs avec les poules correspondantes

use App\Entity\Club;
use App\Entity\Poule;
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

// Récupérer les poules
$pouleRepo = $entityManager->getRepository(Poule::class);
$poules = $pouleRepo->findAll();

if (count($poules) < 4) {
    echo "Il faut au moins 4 poules dans la base de données.\n";
    exit(1);
}

// Récupérer les clubs
$clubRepo = $entityManager->getRepository(Club::class);
$clubs = $clubRepo->findAll();

if (count($clubs) < 16) {
    echo "Il faut au moins 16 clubs dans la base de données.\n";
    exit(1);
}

// Répartition des clubs dans les poules
for ($i = 0; $i < 16; $i++) {
    $club = $clubs[$i];
    $pouleIndex = intdiv($i, 4); // 0 à 3
    $club->setPoule($poules[$pouleIndex]);
    $entityManager->persist($club);
}

$entityManager->flush();

echo "Les clubs ont été répartis dans les 4 poules avec succès.\n";

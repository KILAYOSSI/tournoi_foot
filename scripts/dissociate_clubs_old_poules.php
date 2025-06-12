<?php
// Script PHP pour dissocier les clubs des anciennes poules "Poule 1", "Poule 2", "Poule 3", "Poule 4"

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

// Récupérer les poules anciennes
$pouleRepo = $entityManager->getRepository(Poule::class);
$oldPoules = $pouleRepo->createQueryBuilder('p')
    ->where('p.nom IN (:names)')
    ->setParameter('names', ['Poule 1', 'Poule 2', 'Poule 3', 'Poule 4'])
    ->getQuery()
    ->getResult();

if (count($oldPoules) === 0) {
    echo "Aucune ancienne poule trouvée.\n";
    exit(0);
}

// Dissocier les clubs des anciennes poules
$clubRepo = $entityManager->getRepository(Club::class);
foreach ($oldPoules as $poule) {
    $clubs = $clubRepo->findBy(['poule' => $poule]);
    foreach ($clubs as $club) {
        $club->setPoule(null);
        $entityManager->persist($club);
    }
}

$entityManager->flush();

echo "Clubs dissociés des anciennes poules avec succès.\n";

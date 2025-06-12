<?php
// Script PHP pour lister les noms des poules en base

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

echo "Liste des poules en base :\n";
foreach ($poules as $poule) {
    echo "- " . $poule->getNom() . "\n";
}

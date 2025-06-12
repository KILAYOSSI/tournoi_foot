<?php
// Script PHP pour vérifier si la poule "Poule D" existe en base

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

// Rechercher la poule "Poule D"
$pouleRepo = $entityManager->getRepository(Poule::class);
$pouleD = $pouleRepo->findOneBy(['nom' => 'Poule D']);

if ($pouleD) {
    echo "La poule 'Poule D' existe en base.\n";
} else {
    echo "La poule 'Poule D' n'existe pas en base.\n";
}

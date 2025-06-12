<?php
// Script PHP pour insérer 4 poules dans la table poule

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

$poules = ['Poule A', 'Poule B', 'Poule C', 'Poule D'];

foreach ($poules as $nom) {
    $poule = new Poule();
    $poule->setNom($nom);
    $entityManager->persist($poule);
}

$entityManager->flush();

echo "4 poules insérées avec succès.\n";

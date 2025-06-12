<?php
// Script PHP pour supprimer les anciennes poules "Poule 1", "Poule 2", "Poule 3", "Poule 4"

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

// Récupérer les poules à supprimer
$pouleRepo = $entityManager->getRepository(Poule::class);
$oldPoules = $pouleRepo->createQueryBuilder('p')
    ->where('p.nom IN (:names)')
    ->setParameter('names', ['Poule 1', 'Poule 2', 'Poule 3', 'Poule 4'])
    ->getQuery()
    ->getResult();

foreach ($oldPoules as $poule) {
    $entityManager->remove($poule);
}

$entityManager->flush();

echo "Anciennes poules supprimées avec succès.\n";

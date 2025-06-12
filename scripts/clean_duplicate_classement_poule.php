<?php
// Script PHP pour nettoyer les doublons dans la table classement_poule
// en conservant une seule entrée par club et poule

use App\Entity\ClassementPoule;
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

// Récupérer tous les classements
$classements = $entityManager->getRepository(ClassementPoule::class)->findAll();

$seen = [];
$toRemove = [];

foreach ($classements as $classement) {
    $key = $classement->getClub()->getId() . '-' . $classement->getPoule()->getId();
    if (isset($seen[$key])) {
        // Doublon détecté, à supprimer
        $toRemove[] = $classement;
    } else {
        $seen[$key] = true;
    }
}

// Supprimer les doublons
foreach ($toRemove as $dup) {
    $entityManager->remove($dup);
}

$entityManager->flush();

echo "Nettoyage des doublons dans classement_poule terminé. " . count($toRemove) . " doublons supprimés.\n";

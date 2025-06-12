<?php
// Script PHP corrigé pour vérifier si des clubs sont présents dans plusieurs poules dans classement_poule

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

// Exécuter la requête DQL pour trouver les clubs dans plusieurs poules
$dql = "SELECT IDENTITY(cp.club) as clubId, COUNT(DISTINCT cp.poule) as nbPoules
        FROM App\Entity\ClassementPoule cp
        GROUP BY cp.club
        HAVING COUNT(DISTINCT cp.poule) > 1";

$query = $entityManager->createQuery($dql);
$results = $query->getResult();

if (empty($results)) {
    echo "Aucun club présent dans plusieurs poules.\n";
} else {
    echo "Clubs présents dans plusieurs poules :\n";
    foreach ($results as $row) {
        $clubId = $row['clubId'];
        $nbPoules = $row['nbPoules'];
        echo "- Club ID: $clubId dans $nbPoules poules\n";
    }
}

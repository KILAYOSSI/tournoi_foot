<?php
// Script pour vérifier les données dans la table classement_poule

require_once __DIR__ . '/../vendor/autoload.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$paths = [__DIR__ . '/../src/Entity'];
$isDevMode = true;

// Charger les variables d'environnement
$dotenv = new Symfony\Component\Dotenv\Dotenv();
$dotenv->load(__DIR__ . '/../.env');

$dbParams = [
    'url' => getenv('DATABASE_URL'),
];

// Correction pour doctrine/annotations v2
$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);
$entityManager = EntityManager::create($dbParams, $config);

$query = $entityManager->createQuery('SELECT c FROM App\Entity\ClassementPoule c');
$classements = $query->getResult();

echo "Nombre de classements trouvés : " . count($classements) . PHP_EOL;

foreach ($classements as $classement) {
    echo "Club: " . $classement->getClub()->getNom() . " - Points: " . $classement->getPoints() . " - Rang: " . $classement->getRang() . PHP_EOL;
}

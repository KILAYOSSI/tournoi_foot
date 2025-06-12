<?php
// Script pour nettoyer les doublons et entrées vides dans la table classement_poule

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once __DIR__ . '/../vendor/autoload.php';

// Configuration Doctrine
$paths = [__DIR__ . '/../src/Entity'];
$isDevMode = true;

// Config DB - à adapter selon votre config
$dbParams = [
    'driver'   => 'pdo_mysql',
    'user'     => 'root',
    'password' => '',
    'dbname'   => 'gametournoi',
];

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);
$entityManager = EntityManager::create($dbParams, $config);

$conn = $entityManager->getConnection();

// Supprimer les entrées où club_id est NULL
$conn->executeStatement('DELETE FROM classement_poule WHERE club_id IS NULL');

// Supprimer les doublons en gardant la plus récente (par id)
$sql = "
DELETE c1 FROM classement_poule c1
INNER JOIN classement_poule c2 
WHERE 
    c1.id > c2.id
    AND c1.club_id = c2.club_id
";
$conn->executeStatement($sql);

echo "Nettoyage des doublons et entrées vides effectué.\n";
?>

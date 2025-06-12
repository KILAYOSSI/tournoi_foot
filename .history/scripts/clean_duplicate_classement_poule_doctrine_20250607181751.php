<?php
// Script PHP pour supprimer les doublons dans classement_poule via Doctrine en conservant la première entrée par club et poule

use Doctrine\DBAL\Connection;
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

// Récupérer la connexion Doctrine DBAL
/** @var Connection $conn */
$conn = $container->get('doctrine.dbal.default_connection');

// Requête SQL pour supprimer les doublons en conservant la plus petite id
$sql = "
DELETE cp1 FROM classement_poule cp1
INNER JOIN classement_poule cp2 
WHERE 
    cp1.id > cp2.id
    AND cp1.club_id = cp2.club_id
    AND cp1.poule_id = cp2.poule_id
";

$stmt = $conn->prepare($sql);
$stmt->execute();

echo "Nettoyage des doublons dans classement_poule via Doctrine terminé.\n";

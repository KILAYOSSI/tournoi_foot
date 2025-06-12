<?php
// Script PHP pour nettoyer explicitement les doublons dans la table classement_poule

use App\Kernel;
use Doctrine\DBAL\Connection;

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

// Charger les variables d'environnement depuis .env
if (file_exists(__DIR__ . '/../.env')) {
    (new Dotenv())->load(__DIR__ . '/../.env');
}

$kernel = new Kernel('dev', true);
$kernel->boot();

$connection = $kernel->getContainer()->get('doctrine')->getConnection();

// Requête SQL pour supprimer les doublons dans classement_poule en gardant la plus ancienne entrée
$sql = "
DELETE cp1 FROM classement_poule cp1
INNER JOIN classement_poule cp2
WHERE
    cp1.id > cp2.id
    AND cp1.club_id = cp2.club_id
    AND cp1.poule_id = cp2.poule_id
";

$affectedRows = $connection->executeStatement($sql);

echo "Nettoyage explicite des doublons dans classement_poule terminé. $affectedRows doublons supprimés.\n";

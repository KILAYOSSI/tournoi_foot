<?php
use Doctrine\DBAL\DriverManager;

require __DIR__ . '/../vendor/autoload.php';

$config = new \Doctrine\DBAL\Configuration();

$connectionParams = [
    'dbname' => 'gametournoi',
    'user' => 'root', // adapter selon votre config
    'password' => '', // adapter selon votre config
    'host' => '127.0.0.1',
    'driver' => 'pdo_mysql',
];

$conn = DriverManager::getConnection($connectionParams, $config);

$sql = "SELECT cp.id, p.nom as poule, c.nom as club, cp.points, cp.matchsjouer, cp.gagnes, cp.nuls, cp.perdus, cp.buts_pour, cp.butscontre, cp.goalaverage
FROM classement_poule cp
JOIN club c ON cp.club_id = c.id
JOIN poule p ON c.poule_id = p.id
ORDER BY p.nom, cp.points DESC";

$stmt = $conn->executeQuery($sql);
$results = $stmt->fetchAllAssociative();

echo "Données de classement par poule :\n";
foreach ($results as $row) {
    echo sprintf(
        "ID: %d | Poule: %s | Club: %s | Points: %d | J: %d | G: %d | N: %d | P: %d | BP: %d | BC: %d | Diff: %d\n",
        $row['id'],
        $row['poule'],
        $row['club'],
        $row['points'],
        $row['matchsjouer'],
        $row['gagnes'],
        $row['nuls'],
        $row['perdus'],
        $row['buts_pour'],
        $row['butscontre'],
        $row['goalaverage']
    );
}

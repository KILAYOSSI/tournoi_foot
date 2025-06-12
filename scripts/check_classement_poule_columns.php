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

$schemaManager = $conn->createSchemaManager();
$columns = $schemaManager->listTableColumns('classement_poule');

echo "Colonnes de la table classement_poule :\n";
foreach ($columns as $column) {
    echo $column->getName() . "\n";
}

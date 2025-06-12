<?php
// scripts/clear_database.php

use Doctrine\ORM\EntityManagerInterface;

require_once __DIR__ . '/../vendor/autoload.php';

$kernel = new \App\Kernel('dev', true);
$kernel->boot();

/** @var EntityManagerInterface $entityManager */
$entityManager = $kernel->getContainer()->get('doctrine')->getManager();

$connection = $entityManager->getConnection();

$connection->beginTransaction();

try {
    $platform = $connection->getDatabasePlatform();

    // Désactiver les contraintes de clé étrangère
    $connection->executeStatement('SET FOREIGN_KEY_CHECKS=0');

    // Vider les tables (ajustez les noms des tables selon votre base)
    $connection->executeStatement($platform->getTruncateTableSQL('vote', true));
    $connection->executeStatement($platform->getTruncateTableSQL('rencontre', true));
    $connection->executeStatement($platform->getTruncateTableSQL('match', true));
    $connection->executeStatement($platform->getTruncateTableSQL('joueur', true));
    $connection->executeStatement($platform->getTruncateTableSQL('club', true));
    $connection->executeStatement($platform->getTruncateTableSQL('classement_poule', true));

    // Réactiver les contraintes de clé étrangère
    $connection->executeStatement('SET FOREIGN_KEY_CHECKS=1');

    $connection->commit();

    echo "Toutes les données des tables liées au tournoi ont été supprimées.\n";
} catch (\Exception $e) {
    $connection->rollBack();
    echo "Erreur lors de la suppression des données : " . $e->getMessage() . "\n";
}

<?php
// Script pour insérer une statistique de test dans la base de données

use App\Entity\Statistiquematch;
use Doctrine\ORM\EntityManagerInterface;

require_once __DIR__ . '/../vendor/autoload.php';

$kernel = new \App\Kernel('dev', true);
$kernel->boot();

/** @var EntityManagerInterface $entityManager */
$entityManager = $kernel->getContainer()->get('doctrine')->getManager();

$statistique = new Statistiquematch();
// Remplir les propriétés nécessaires de l'entité Statistiquematch ici
// Exemple : $statistique->setSomeField('valeur');

$entityManager->persist($statistique);
$entityManager->flush();

echo "Statistique de test insérée avec l'id : " . $statistique->getId() . PHP_EOL;

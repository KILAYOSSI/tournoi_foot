<?php
// Script corrigé pour charger l'environnement Symfony et accéder à la base de données

use App\Kernel;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Poule;
use App\Entity\Club;

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

// Charger les variables d'environnement depuis .env
if (file_exists(__DIR__ . '/../.env')) {
    (new Dotenv())->load(__DIR__ . '/../.env');
}

$kernel = new Kernel('dev', true);
$kernel->boot();

/** @var EntityManagerInterface $entityManager */
$entityManager = $kernel->getContainer()->get('doctrine')->getManager();

// 1. Supprimer toutes les poules en doublon et garder uniquement 4 poules A, B, C, D
$pouleRepo = $entityManager->getRepository(Poule::class);
$poules = $pouleRepo->findAll();

$poulesToKeep = ['Poule A', 'Poule B', 'Poule C', 'Poule D'];
$poulesSeen = [];

foreach ($poules as $poule) {
    $nom = trim($poule->getNom());
    if (in_array($nom, $poulesToKeep)) {
        if (in_array($nom, $poulesSeen)) {
            // Doublon, supprimer
            $entityManager->remove($poule);
        } else {
            $poulesSeen[] = $nom;
        }
    } else {
        // Poule non désirée, supprimer
        $entityManager->remove($poule);
    }
}
$entityManager->flush();

// 2. Recharger les poules A, B, C, D (après nettoyage)
$pouleA = $pouleRepo->findOneBy(['nom' => 'Poule A']);
$pouleB = $pouleRepo->findOneBy(['nom' => 'Poule B']);
$pouleC = $pouleRepo->findOneBy(['nom' => 'Poule C']);
$pouleD = $pouleRepo->findOneBy(['nom' => 'Poule D']);

$poulesList = [$pouleA, $pouleB, $pouleC, $pouleD];

// 3. Supprimer les doublons de clubs dans les poules (un club ne doit être que dans une seule poule)
$clubRepo = $entityManager->getRepository(Club::class);
$clubs = $clubRepo->findAll();

$clubIdsSeen = [];
$idsPouleD = [64, 60, 52, 56]; // Clubs à assigner à la poule D selon la demande
$idsPouleC = []; // On vide cette liste pour gérer la poule C manuellement plus bas
$clubsPouleD = [];
$clubsPouleC = [];
foreach ($clubs as $club) {
    $clubId = $club->getId();
    if (in_array($clubId, $idsPouleD)) {
        $clubsPouleD[] = $club;
        // Assigner directement à la poule D
        $club->setPoule($pouleD);
        $entityManager->persist($club);
    } elseif (in_array($clubId, $idsPouleC)) {
        $clubsPouleC[] = $club;
        // Assigner directement à la poule C
        $club->setPoule($pouleC);
        $entityManager->persist($club);
    } else {
        $poule = $club->getPoule();
        if ($poule && in_array($poule, $poulesList)) {
            if (in_array($clubId, $clubIdsSeen)) {
                // Club en doublon, désassigner la poule
                $club->setPoule(null);
                $entityManager->persist($club);
            } else {
                $clubIdsSeen[] = $clubId;
            }
        } else {
            // Club sans poule ou poule non valide, désassigner la poule
            $club->setPoule(null);
            $entityManager->persist($club);
        }
    }
}
$entityManager->flush();

// 4. Répartir les clubs dans les 4 poules A, B, C, D avec 4 clubs par poule
// En tenant compte que certains clubs doivent être dans la poule D
$clubsCountPerPoule = [0, 0, 0, 0];
$clubsToAssign = [];

// Récupérer tous les clubs
$allClubs = $clubRepo->findAll();

// Assigner directement les clubs à la poule D
foreach ($allClubs as $club) {
    $clubId = $club->getId();
    if (in_array($clubId, $idsPouleD)) {
        $club->setPoule($pouleD);
        $entityManager->persist($club);
        $clubsCountPerPoule[3]++;
    } else {
        $clubsToAssign[] = $club;
    }
}

// Supprimer les doublons dans la poule C et garder exactement 4 clubs
$clubsInPouleC = $clubRepo->findBy(['poule' => $pouleC]);
$clubsIdsToKeepInC = [];
$clubsCountInC = 0;
foreach ($clubsInPouleC as $club) {
    if ($clubsCountInC < 4) {
        $clubsIdsToKeepInC[] = $club->getId();
        $clubsCountInC++;
    } else {
        // Désassigner les clubs en trop dans la poule C
        $club->setPoule(null);
        $entityManager->persist($club);
    }
}
$clubsCountPerPoule[2] = $clubsCountInC;

// Répartir les autres clubs dans les poules A et B
$pouleIndex = 0;
foreach ($clubsToAssign as $club) {
    $clubId = $club->getId();
    // Ne pas réassigner les clubs déjà dans la poule D ou C
    if (in_array($clubId, $idsPouleD) || in_array($clubId, $clubsIdsToKeepInC)) {
        continue;
    }
    while ($clubsCountPerPoule[$pouleIndex] >= 4) {
        $pouleIndex = ($pouleIndex + 1) % 2; // 0,1 pour A,B
    }
    $club->setPoule($poulesList[$pouleIndex]);
    $entityManager->persist($club);
    $clubsCountPerPoule[$pouleIndex]++;
    $pouleIndex = ($pouleIndex + 1) % 2;
}

$entityManager->flush();

echo "Répartition propre des clubs dans les 4 poules A, B, C, D effectuée.\n";

// 6. Relancer la mise à jour des classements
echo "Lancement de la mise à jour des classements...\n";
passthru('php bin/console app:update-classement-poule');

echo "Script terminé.\n";

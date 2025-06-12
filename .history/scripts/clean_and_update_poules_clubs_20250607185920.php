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
foreach ($clubs as $club) {
    $poule = $club->getPoule();
    if ($poule && in_array($poule, $poulesList)) {
        $clubId = $club->getId();
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
$entityManager->flush();

// 4. Répartir les clubs non assignés dans les poules pour avoir exactement 4 clubs par poule
// Compter les clubs par poule
$clubsCountPerPoule = [0, 0, 0, 0];
foreach ($clubs as $club) {
    $poule = $club->getPoule();
    if ($poule) {
        $index = array_search($poule, $poulesList);
        if ($index !== false) {
            $clubsCountPerPoule[$index]++;
        }
    }
}

// Assigner les clubs non assignés
foreach ($clubs as $club) {
    if ($club->getPoule() === null) {
        // Trouver une poule avec moins de 4 clubs
        $assigned = false;
        for ($i = 0; $i < 4; $i++) {
            if ($clubsCountPerPoule[$i] < 4) {
                $club->setPoule($poulesList[$i]);
                $entityManager->persist($club);
                $clubsCountPerPoule[$i]++;
                $assigned = true;
                break;
            }
        }
        // Si toutes les poules ont 4 clubs, assigner à la poule D (index 3)
        if (!$assigned) {
            $club->setPoule($pouleD);
            $entityManager->persist($club);
            $clubsCountPerPoule[3]++;
        }
    }
}
$entityManager->flush();

$entityManager->flush();

// 5. Rééquilibrer les poules pour qu'elles aient exactement 4 clubs
// Si une poule a plus de 4 clubs, déplacer les excédents vers la poule D
for ($i = 0; $i < 3; $i++) { // Pour les poules A, B, C
    while ($clubsCountPerPoule[$i] > 4) {
        $pouleSource = $poulesList[$i];
        $clubsInPoule = $clubRepo->findBy(['poule' => $pouleSource]);
        $moved = 0;
        foreach ($clubsInPoule as $club) {
            if ($clubsCountPerPoule[$i] <= 4) {
                break;
            }
            // Ne pas déplacer les clubs déjà dans la poule D
            if ($club->getPoule()->getNom() === 'Poule D') {
                continue;
            }
            $club->setPoule($pouleD);
            $entityManager->persist($club);
            $clubsCountPerPoule[$i]--;
            $clubsCountPerPoule[3]++;
            $moved++;
            break; // déplacer un club à la fois
        }
    }
}
$entityManager->flush();

echo "Répartition propre des clubs dans les 4 poules A, B, C, D effectuée.\n";

// 6. Relancer la mise à jour des classements
echo "Lancement de la mise à jour des classements...\n";
passthru('php bin/console app:update-classement-poule');

echo "Script terminé.\n";

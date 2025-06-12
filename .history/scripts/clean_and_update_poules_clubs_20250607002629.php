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

// 3. Répartir exactement 4 clubs dans chaque poule A, B, C, D
$clubRepo = $entityManager->getRepository(Club::class);
$clubs = $clubRepo->findAll();

$poulesList = [$pouleA, $pouleB, $pouleC, $pouleD];
$clubsCountPerPoule = [0, 0, 0, 0];
$pouleIndex = 0;

foreach ($clubs as $club) {
    // Trouver la prochaine poule avec moins de 4 clubs
    while ($clubsCountPerPoule[$pouleIndex] >= 4) {
        $pouleIndex = ($pouleIndex + 1) % 4;
    }
    // Assigner le club à la poule
    $club->setPoule($poulesList[$pouleIndex]);
    $entityManager->persist($club);
    $clubsCountPerPoule[$pouleIndex]++;
    $pouleIndex = ($pouleIndex + 1) % 4;
}

// 4. Supprimer les clubs non assignés (au-delà des 16 premiers)
foreach ($clubs as $club) {
    if (!in_array($club->getPoule(), $poulesList)) {
        $entityManager->remove($club);
    }
}
$entityManager->flush();

echo "Répartition propre des clubs dans les 4 poules A, B, C, D effectuée.\n";

// 5. Relancer la mise à jour des classements
echo "Lancement de la mise à jour des classements...\n";
passthru('php bin/console app:update-classement-poule');

echo "Script terminé.\n";

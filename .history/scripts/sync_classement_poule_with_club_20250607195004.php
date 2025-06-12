<?php
// Script pour synchroniser la table classement_poule avec la table club
// Supprime les doublons dans classement_poule
// Assure que les clubs avec les IDs 60, 64, 52, 56 sont bien dans la poule 20
// Assure que les clubs avec les IDs 51, 55, 59, 63 sont bien dans la poule C
// Supprime les doublons avec les IDs 55, 51, 63, 59

use App\Kernel;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ClassementPoule;
use App\Entity\Club;
use App\Entity\Poule;

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

$classementRepo = $entityManager->getRepository(ClassementPoule::class);
$clubRepo = $entityManager->getRepository(Club::class);
$pouleRepo = $entityManager->getRepository(Poule::class);

// Poules cibles
$poule20 = $pouleRepo->find(20);
$pouleC = $pouleRepo->findOneBy(['nom' => 'Poule C']);
if (!$poule20) {
    echo "La poule avec l'id 20 n'existe pas.\n";
    exit(1);
}
if (!$pouleC) {
    echo "La poule C n'existe pas.\n";
    exit(1);
}

// IDs des clubs à assurer dans la poule 20
$clubIdsPoule20 = [60, 64, 52, 56];
// IDs des clubs à assurer dans la poule C
$clubIdsPouleC = [51, 55, 59, 63];
// IDs des clubs à supprimer des doublons
$clubIdsToRemove = [55, 51, 63, 59];

// 1. Supprimer les doublons dans classement_poule (garder une seule entrée par club)
$classements = $classementRepo->findAll();

$seen = [];
foreach ($classements as $classement) {
    $clubId = $classement->getClub()->getId();
    if (isset($seen[$clubId])) {
        $entityManager->remove($classement);
    } else {
        $seen[$clubId] = true;
    }
}
$entityManager->flush();

// 2. Supprimer les entrées avec les clubIdsToRemove
foreach ($clubIdsToRemove as $clubId) {
    $classement = $classementRepo->findOneBy(['club' => $clubId]);
    if ($classement) {
        $entityManager->remove($classement);
    }
}
$entityManager->flush();

// 3. Assurer que les clubs avec clubIdsPoule20 sont dans la poule 20
foreach ($clubIdsPoule20 as $clubId) {
    $club = $clubRepo->find($clubId);
    if (!$club) {
        echo "Club avec ID $clubId non trouvé.\n";
        continue;
    }
    $classement = $classementRepo->findOneBy(['club' => $clubId]);
    if (!$classement) {
        // Créer un nouveau classement pour ce club dans la poule 20
        $classement = new ClassementPoule();
        $classement->setClub($club);
        $classement->setPoule($poule20);
        // Initialiser les champs obligatoires avec des valeurs par défaut
        $classement->setPoints(0);
        $classement->setRang(0);
        $classement->setMatchsjouer(0);
        $classement->setGagnes(0);
        $classement->setNuls(0);
        $classement->setPerdus(0);
        $classement->setButsPour(0);
        $classement->setButscontre(0);
        $classement->setGoalaverage(0);
        $entityManager->persist($classement);
    } else {
        // Mettre à jour la poule si nécessaire
        if ($classement->getPoule()->getId() !== 20) {
            $classement->setPoule($poule20);
            $entityManager->persist($classement);
        }
    }
}
$entityManager->flush();

// 4. Assurer que les clubs avec clubIdsPouleC sont dans la poule C
foreach ($clubIdsPouleC as $clubId) {
    $club = $clubRepo->find($clubId);
    if (!$club) {
        echo "Club avec ID $clubId non trouvé.\n";
        continue;
    }
    $classement = $classementRepo->findOneBy(['club' => $clubId]);
    if (!$classement) {
        // Créer un nouveau classement pour ce club dans la poule C
        $classement = new ClassementPoule();
        $classement->setClub($club);
        $classement->setPoule($pouleC);
        // Initialiser les champs obligatoires avec des valeurs par défaut
        $classement->setPoints(0);
        $classement->setRang(0);
        $classement->setMatchsjouer(0);
        $classement->setGagnes(0);
        $classement->setNuls(0);
        $classement->setPerdus(0);
        $classement->setButsPour(0);
        $classement->setButscontre(0);
        $classement->setGoalaverage(0);
        $entityManager->persist($classement);
    } else {
        // Mettre à jour la poule si nécessaire
        if ($classement->getPoule()->getId() !== $pouleC->getId()) {
            $classement->setPoule($pouleC);
            $entityManager->persist($classement);
        }
    }
}
$entityManager->flush();

echo "Synchronisation de classement_poule avec club terminée.\n";

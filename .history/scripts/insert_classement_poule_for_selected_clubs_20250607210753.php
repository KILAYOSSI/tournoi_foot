<?php
// Script pour insérer dans la table classement_poule les clubs sélectionnés dans les poules C (id 19) et D (id 20)

use App\Kernel;
use App\Entity\Club;
use App\Entity\Poule;
use App\Entity\ClassementPoule;
use Doctrine\ORM\EntityManagerInterface;

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

$clubRepo = $entityManager->getRepository(Club::class);
$pouleRepo = $entityManager->getRepository(Poule::class);
$classementRepo = $entityManager->getRepository(ClassementPoule::class);

// IDs des clubs à insérer
$idsPouleC = [63, 59, 55, 51];
$idsPouleD = [64, 60, 56, 52];

// IDs des poules
$pouleCId = 19;
$pouleDId = 20;

// Récupérer les poules
$pouleC = $pouleRepo->find($pouleCId);
$pouleD = $pouleRepo->find($pouleDId);

if (!$pouleC || !$pouleD) {
    echo "Erreur : Poule C ou D introuvable avec les IDs spécifiés.\n";
    exit(1);
}

// Supprimer les anciens classements pour ces clubs dans ces poules
$oldClassements = $classementRepo->createQueryBuilder('cp')
    ->where('cp.poule = :pouleC AND cp.club IN (:clubsC)')
    ->orWhere('cp.poule = :pouleD AND cp.club IN (:clubsD)')
    ->setParameter('pouleC', $pouleC)
    ->setParameter('clubsC', $idsPouleC)
    ->setParameter('pouleD', $pouleD)
    ->setParameter('clubsD', $idsPouleD)
    ->getQuery()
    ->getResult();

foreach ($oldClassements as $oldClassement) {
    $entityManager->remove($oldClassement);
}
$entityManager->flush();

// Insérer les nouveaux classements avec stats initialisées à zéro
foreach ($idsPouleC as $clubId) {
    $club = $clubRepo->find($clubId);
    if ($club) {
        $classement = new ClassementPoule();
        $classement->setClub($club);
        $classement->setPoule($pouleC);
        $classement->setPoints(0);
        $classement->setButsPour(0);
        $classement->setButscontre(0);
        $classement->setGoalaverage(0);
        $classement->setMatchsjouer(0);
        $classement->setGagnes(0);
        $classement->setNuls(0);
        $classement->setPerdus(0);
        $classement->setRang(0);
        $classement->setCartonsJaunes(0);
        $classement->setCartonsRouges(0);
        $entityManager->persist($classement);
    }
}

foreach ($idsPouleD as $clubId) {
    $club = $clubRepo->find($clubId);
    if ($club) {
        $classement = new ClassementPoule();
        $classement->setClub($club);
        $classement->setPoule($pouleD);
        $classement->setPoints(0);
        $classement->setButsPour(0);
        $classement->setButscontre(0);
        $classement->setGoalaverage(0);
        $classement->setMatchsjouer(0);
        $classement->setGagnes(0);
        $classement->setNuls(0);
        $classement->setPerdus(0);
        $classement->setRang(0);
        $classement->setCartonsJaunes(0);
        $classement->setCartonsRouges(0);
        $entityManager->persist($classement);
    }
}

$entityManager->flush();

echo "Insertion des clubs dans les poules C et D dans la table classement_poule terminée.\n";

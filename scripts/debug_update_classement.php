<?php
// Script de debug pour vérifier le fonctionnement du service UpdateClassementService

use App\Kernel;
use App\Service\UpdateClassementService;
use Doctrine\ORM\EntityManagerInterface;

require __DIR__ . '/../vendor/autoload.php';

$kernel = new Kernel('dev', true);
$kernel->boot();

$container = $kernel->getContainer();

/** @var UpdateClassementService $updateClassementService */
$updateClassementService = $container->get(UpdateClassementService::class);

/** @var EntityManagerInterface $entityManager */
$entityManager = $container->get('doctrine.orm.entity_manager');

echo "Lancement de la mise à jour des classements...\n";

$updateClassementService->updateClassements();

echo "Mise à jour terminée.\n";

// Vérification des données en base
$classementRepo = $entityManager->getRepository(\App\Entity\ClassementPoule::class);
$classements = $classementRepo->findAll();

echo "Nombre de classements en base : " . count($classements) . "\n";

foreach ($classements as $classement) {
    echo sprintf(
        "Club: %s, Poule: %s, Rang: %d, Points: %d\n",
        $classement->getClub() ? $classement->getClub()->getNom() : 'N/A',
        $classement->getPoule() ? $classement->getPoule()->getNom() : 'N/A',
        $classement->getRang(),
        $classement->getPoints()
    );
}

echo "Fin du debug.\n";

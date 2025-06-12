<?php
// Script pour vérifier qu'il y a exactement 4 clubs dans chaque poule dans la table classement_poule

use App\Kernel;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Poule;
use App\Entity\ClassementPoule;

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

$pouleRepo = $entityManager->getRepository(Poule::class);
$classementRepo = $entityManager->getRepository(ClassementPoule::class);

$poulesToCheck = ['Poule A', 'Poule B', 'Poule C', 'Poule D'];

foreach ($poulesToCheck as $pouleName) {
    $poule = $pouleRepo->findOneBy(['nom' => $pouleName]);
    if (!$poule) {
        echo "La poule $pouleName n'existe pas.\n";
        continue;
    }
    $clubsCount = $classementRepo->count(['poule' => $poule]);
    echo "Poule $pouleName : $clubsCount clubs\n";
    if ($clubsCount !== 4) {
        echo "  -> Attention : nombre incorrect de clubs dans $pouleName\n";
    }
}

<?php
// Script pour lister toutes les dates des matchs enregistrés en base

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Rencontre;
use Symfony\Component\Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

// Charger les variables d'environnement depuis .env
if (file_exists(__DIR__ . '/../.env')) {
    (new Dotenv())->load(__DIR__ . '/../.env');
}

$kernel = new \App\Kernel('dev', true);
$kernel->boot();

/** @var EntityManagerInterface $entityManager */
$entityManager = $kernel->getContainer()->get('doctrine')->getManager();

$rencontreRepository = $entityManager->getRepository(Rencontre::class);

$allMatchs = $rencontreRepository->createQueryBuilder('r')
    ->orderBy('r.dateHeure', 'ASC')
    ->getQuery()
    ->getResult();

echo "Toutes les dates des matchs enregistrés :\n";
if (count($allMatchs) === 0) {
    echo "Aucun match trouvé en base.\n";
} else {
    foreach ($allMatchs as $match) {
        $dateStr = $match->getDateHeure()->format('Y-m-d H:i:s');
        $club1 = $match->getClub1() ? $match->getClub1()->getNom() : 'N/A';
        $club2 = $match->getClub2() ? $match->getClub2()->getNom() : 'N/A';
        echo "- $dateStr : $club1 vs $club2\n";
    }
}

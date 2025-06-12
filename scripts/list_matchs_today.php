<?php
// Script pour lister les matchs programmés aujourd'hui avec leurs dates exactes

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

$today = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
$startOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', $today->format('Y-m-d') . ' 00:00:00', new \DateTimeZone('Europe/Paris'));
$endOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', $today->format('Y-m-d') . ' 23:59:59', new \DateTimeZone('Europe/Paris'));

$rencontreRepository = $entityManager->getRepository(Rencontre::class);

$matchsDuJour = $rencontreRepository->createQueryBuilder('r')
    ->where('r.dateHeure >= :startOfDay')
    ->andWhere('r.dateHeure <= :endOfDay')
    ->setParameter('startOfDay', $startOfDay)
    ->setParameter('endOfDay', $endOfDay)
    ->orderBy('r.dateHeure', 'ASC')
    ->getQuery()
    ->getResult();

echo "Matchs programmés aujourd'hui :\n";
if (count($matchsDuJour) === 0) {
    echo "Aucun match trouvé.\n";
} else {
    foreach ($matchsDuJour as $match) {
        $dateStr = $match->getDateHeure()->format('Y-m-d H:i:s');
        $club1 = $match->getClub1() ? $match->getClub1()->getNom() : 'N/A';
        $club2 = $match->getClub2() ? $match->getClub2()->getNom() : 'N/A';
        echo "- $dateStr : $club1 vs $club2\n";
    }
}

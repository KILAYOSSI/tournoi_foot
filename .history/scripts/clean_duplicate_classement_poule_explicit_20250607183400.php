<?php
// Script PHP pour nettoyer explicitement les doublons dans la table classement_poule

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ClassementPoule;

require_once __DIR__ . '/../vendor/autoload.php';

$kernel = new \App\Kernel('dev', true);
$kernel->boot();

/** @var EntityManagerInterface $entityManager */
$entityManager = $kernel->getContainer()->get('doctrine')->getManager();

$conn = $entityManager->getConnection();

// Requête pour supprimer les doublons en gardant l'id minimum
$sql = "
DELETE cp1 FROM classement_poule cp1
INNER JOIN classement_poule cp2
WHERE
    cp1.id > cp2.id
    AND cp1.club_id = cp2.club_id
    AND cp1.poule_id = cp2.poule_id
";

$stmt = $conn->prepare($sql);
$stmt->executeStatement();

echo "Nettoyage explicite des doublons dans classement_poule terminé.\n";
?>

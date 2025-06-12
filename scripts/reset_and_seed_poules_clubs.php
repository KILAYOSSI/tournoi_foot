<?php
// Ce script supprime les classements existants, crée 4 poules A, B, C, D,
// et ajoute un club par poule pour repartir sur une base propre.

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Poule;
use App\Entity\Club;
use App\Entity\ClassementPoule;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$kernel = new \App\Kernel('dev', true);
$kernel->boot();
$container = $kernel->getContainer();
/** @var EntityManagerInterface $em */
$em = $container->get('doctrine')->getManager();

// Supprimer tous les classements existants
$classements = $em->getRepository(ClassementPoule::class)->findAll();
foreach ($classements as $classement) {
    $em->remove($classement);
}
$em->flush();

// Supprimer tous les clubs existants
$clubs = $em->getRepository(Club::class)->findAll();
foreach ($clubs as $club) {
    $em->remove($club);
}
$em->flush();

// Supprimer toutes les poules existantes
$poules = $em->getRepository(Poule::class)->findAll();
foreach ($poules as $poule) {
    $em->remove($poule);
}
$em->flush();

// Créer 4 poules A, B, C, D
$pouleNoms = ['Poule A', 'Poule B', 'Poule C', 'Poule D'];
$poules = [];
foreach ($pouleNoms as $nom) {
    $poule = new Poule();
    $poule->setNom($nom);
    $em->persist($poule);
    $poules[$nom] = $poule;
}
$em->flush();

// Ajouter un club par poule
foreach ($poules as $nom => $poule) {
    $club = new Club();
    $club->setNom("Club de $nom");
    $club->setPoule($poule);
    $em->persist($club);
}
$em->flush();

echo "Réinitialisation des poules et clubs terminée.\n";
echo "4 poules (A, B, C, D) créées avec un club chacune.\n";
echo "Vous pouvez maintenant relancer la mise à jour des classements.\n";

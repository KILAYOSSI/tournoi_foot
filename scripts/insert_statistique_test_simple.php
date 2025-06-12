<?php
// Script simple d'insertion d'une statistique de test sans dépendance à Symfony Kernel

require_once __DIR__ . '/../vendor/autoload.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Statistiquematch;


use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Statistiquematch;

// Configuration Doctrine (à adapter selon votre configuration)
$paths = [__DIR__ . '/../src/Entity'];
$isDevMode = true;

// Paramètres de connexion à la base de données (à adapter)
$dbParams = [
    'driver'   => 'pdo_mysql',
    'host'     => '127.0.0.1',
    'user'     => 'root', // Remplacez par votre utilisateur MySQL
    'password' => '',     // Remplacez par votre mot de passe MySQL
    'dbname'   => 'gametournoi', // Remplacez par le nom de votre base de données
];

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);
$entityManager = EntityManager::create($dbParams, $config);

// Création manuelle d'un ManagerRegistry simulé pour le repository
class SimpleManagerRegistry implements ManagerRegistry {
    private $em;
    public function __construct($em) { $this->em = $em; }
    public function getDefaultConnectionName() { return 'default'; }
    public function getConnection($name = null) { return null; }
    public function getConnections() { return []; }
    public function getConnectionNames() { return []; }
    public function getDefaultManagerName() { return 'default'; }
    public function getManager($name = null) { return $this->em; }
    public function getManagers() { return ['default' => $this->em]; }
    public function resetManager($name = null) { return $this->em; }
    public function getAliasNamespace($alias) { return ''; }
    public function getManagerNames() { return ['default']; }
    public function getRepository($persistentObject, $persistentManagerName = null) {
        return $this->em->getRepository($persistentObject);
    }
}

$registry = new SimpleManagerRegistry($entityManager);

$statistique = new Statistiquematch();
// Remplir les propriétés nécessaires ici, par exemple:
$reflectionClass = new \ReflectionClass($statistique);
$property = $reflectionClass->getProperty('id');
$property->setAccessible(true);
$property->setValue($statistique, 3);

$statistique->setButs(0);
$statistique->setCartonsJaunes(0);
$statistique->setCartonsRouges(0);
$statistique->setPasses(0);
$statistique->setCleanSheet(0);

// Forcer l'insertion avec l'id 3 en supprimant d'abord l'entrée existante si elle existe
$existing = $registry->getRepository(Statistiquematch::class)->find(3);
if ($existing) {
    $entityManager->remove($existing);
    $entityManager->flush();
}
// Ajouter d'autres propriétés obligatoires si nécessaire

$entityManager->persist($statistique);
$entityManager->flush();

echo "Statistique de test insérée avec l'id : " . $statistique->getId() . PHP_EOL;

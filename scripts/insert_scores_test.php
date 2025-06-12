<?php
use App\Entity\Rencontre;
use Symfony\Component\Dotenv\Dotenv;
use App\Kernel;

require_once __DIR__ . '/../vendor/autoload.php';

// Charger les variables d'environnement
if (file_exists(__DIR__ . '/../.env')) {
    (new Dotenv())->load(__DIR__ . '/../.env');
}

// Initialiser le kernel Symfony
$kernel = new Kernel($_SERVER['APP_ENV'] ?? 'dev', (bool) ($_SERVER['APP_DEBUG'] ?? true));
$kernel->boot();

// Récupérer le conteneur de services
$container = $kernel->getContainer();

// Récupérer l'EntityManager
$entityManager = $container->get('doctrine')->getManager();

// Récupérer les rencontres
$rencontreRepo = $entityManager->getRepository(Rencontre::class);
$rencontres = $rencontreRepo->findAll();

if (empty($rencontres)) {
    echo "Aucune rencontre trouvée.\n";
    exit(1);
}

// Insérer des scores de test
foreach ($rencontres as $index => $rencontre) {
    $score1 = rand(0, 3);
    $score2 = rand(0, 3);
    $rencontre->setScoreclub1($score1);
    $rencontre->setScoreclub2($score2);
    $rencontre->setJoue(true);
    $entityManager->persist($rencontre);
}

$entityManager->flush();

echo "Scores de test insérés dans les rencontres.\n";

<?php

namespace App\DataFixtures;

use App\Entity\Club;
use App\Entity\Poule;
use App\Entity\Rencontre;
use App\Entity\Joueur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Création de poules A et B uniquement
        $pouleA = new Poule();
        $pouleA->setNom('Poule A');
        $manager->persist($pouleA);

        $pouleB = new Poule();
        $pouleB->setNom('Poule B');
        $manager->persist($pouleB);

        $pouleC = new Poule();
        $pouleC->setNom('Poule C');
        $manager->persist($pouleC);

        $pouleD = new Poule();
        $pouleD->setNom('Poule D');
        $manager->persist($pouleD);

        // Création de clubs pour Poule A
        $clubParis = new Club();
        $clubParis->setNom('Paris');
        $clubParis->setLogo('paris.png');
        $clubParis->setPoule($pouleA);
        $manager->persist($clubParis);

        $clubRennes = new Club();
        $clubRennes->setNom('Rennes');
        $clubRennes->setLogo('rennes.png');
        $clubRennes->setPoule($pouleA);
        $manager->persist($clubRennes);

        $clubLyon = new Club();
        $clubLyon->setNom('Lyon');
        $clubLyon->setLogo('lyon.png');
        $clubLyon->setPoule($pouleA);
        $manager->persist($clubLyon);

        $clubMarseille = new Club();
        $clubMarseille->setNom('Marseille');
        $clubMarseille->setLogo('marseille.png');
        $clubMarseille->setPoule($pouleA);
        $manager->persist($clubMarseille);

        // Création de clubs pour Poule B
        $clubBrest = new Club();
        $clubBrest->setNom('Brest');
        $clubBrest->setLogo('brest.png');
        $clubBrest->setPoule($pouleB);
        $manager->persist($clubBrest);

        $clubNice = new Club();
        $clubNice->setNom('Nice');
        $clubNice->setLogo('nice.png');
        $clubNice->setPoule($pouleB);
        $manager->persist($clubNice);

        $clubToulouse = new Club();
        $clubToulouse->setNom('Toulouse');
        $clubToulouse->setLogo('toulouse.png');
        $clubToulouse->setPoule($pouleB);
        $manager->persist($clubToulouse);

        $clubStrasbourg = new Club();
        $clubStrasbourg->setNom('Strasbourg');
        $clubStrasbourg->setLogo('strasbourg.png');
        $clubStrasbourg->setPoule($pouleB);
        $manager->persist($clubStrasbourg);

        // Création de joueurs (exemple)
        $joueur1 = new Joueur();
        $joueur1->setNom('Joueur 1');
        $joueur1->setPoste('Attaquant');
        $joueur1->setClub($clubParis);
        $manager->persist($joueur1);

        // Création de rencontres (matchs)
        $match1 = new Rencontre();
        $match1->setClub1($clubParis);
        $match1->setClub2($clubRennes);
        $match1->setDateHeure(new \DateTimeImmutable('today 18:30'));
        $match1->setScoreclub1(0);
        $match1->setScoreclub2(0);
        $match1->setJoue(false);
        $manager->persist($match1);

        $match2 = new Rencontre();
        $match2->setClub1($clubMarseille);
        $match2->setClub2($clubLyon);
        $match2->setDateHeure(new \DateTimeImmutable('today 20:00'));
        $match2->setScoreclub1(2);
        $match2->setScoreclub2(1);
        $match2->setJoue(true);
        $manager->persist($match2);

        $manager->flush();
    }
}

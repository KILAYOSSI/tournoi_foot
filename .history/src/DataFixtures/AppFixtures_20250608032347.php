<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
// use App\Entity\ClassementPoule; // Commented out to remove references

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // ... other fixture loading code ...

        // Commented out ClassementPoule related fixture code
        /*
        $classement = new ClassementPoule();
        $classement->setPoule($poule);
        $classement->setClub($club);
        $classement->setPoints($points);
        $classement->setMatchsjouer($matchsJoues);
        $classement->setGagnes($gagnes);
        $classement->setNuls($nuls);
        $classement->setPerdus($perdus);
        $classement->setButsPour($butsPour);
        $classement->setButscontre($butsContre);
        $classement->setGoalaverage($goalAverage);
        $classement->setRang($rang);
        $manager->persist($classement);
        */

        // ... rest of fixture loading code ...
    }
}

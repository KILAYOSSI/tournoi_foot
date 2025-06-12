<?php

namespace App\Repository;

use App\Entity\Statistiquesmatch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Statistiquesmatch>
 *
 * @method Statistiquesmatch|null find($id, $lockMode = null, $lockVersion = null)
 * @method Statistiquesmatch|null findOneBy(array $criteria, array $orderBy = null)
 * @method Statistiquesmatch[]    findAll()
 * @method Statistiquesmatch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatistiquesmatchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Statistiquesmatch::class);
    }
}

<?php

namespace App\Repository;

use App\Entity\Carton;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Carton>
 *
 * @method Carton|null find($id, $lockMode = null, $lockVersion = null)
 * @method Carton|null findOneBy(array $criteria, array $orderBy = null)
 * @method Carton[]    findAll()
 * @method Carton[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Carton::class);
    }
}

<?php

namespace App\Repository;

use App\Entity\Match;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Match>
 *
 * @method Match|null find($id, $lockMode = null, $lockVersion = null)
 * @method Match|null findOneBy(array $criteria, array $orderBy = null)
 * @method Match[]    findAll()
 * @method Match[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Match::class);
    }

    /**
     * Récupère les matchs du jour
     *
     * @return Match[]
     */
    public function findMatchsDuJour(): array
    {
        $today = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
        $startOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', $today->format('Y-m-d') . ' 00:00:00', new \DateTimeZone('Europe/Paris'));
        $endOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', $today->format('Y-m-d') . ' 23:59:59', new \DateTimeZone('Europe/Paris'));

        return $this->createQueryBuilder('m')
            ->where('m.date >= :startOfDay')
            ->andWhere('m.date <= :endOfDay')
            ->setParameter('startOfDay', $startOfDay)
            ->setParameter('endOfDay', $endOfDay)
            ->orderBy('m.date', 'ASC')
            ->getQuery()
            ->getResult();
    }
}

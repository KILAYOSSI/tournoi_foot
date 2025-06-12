<?php

namespace App\Repository;

use App\Entity\ClassementPoule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ClassementPoule>
 *
 * @method ClassementPoule|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClassementPoule|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClassementPoule[]    findAll()
 * @method ClassementPoule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClassementPouleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClassementPoule::class);
    }

    /**
     * Récupère les classements groupés par poule et triés par rang
     *
     * @return array
     */
    public function findClassementsGroupedByPoule(): array
    {
        $qb = $this->createQueryBuilder('c')
            ->join('c.club', 'club')
            ->join('club.poule', 'poule')
            ->addSelect('club')
            ->addSelect('poule')
            ->orderBy('poule.id', 'ASC')
            ->addOrderBy('c.rang', 'ASC');

        $results = $qb->getQuery()->getResult();

        $grouped = [];
        foreach ($results as $classement) {
            $poule = $classement->getClub()->getPoule();
            if ($poule) {
                $pouleId = $poule->getId();
                if (!isset($grouped[$pouleId])) {
                    $grouped[$pouleId] = [
                        'poule' => $poule,
                        'classements' => [],
                    ];
                }
                $grouped[$pouleId]['classements'][] = $classement;
            }
        }

        return $grouped;
    }
}

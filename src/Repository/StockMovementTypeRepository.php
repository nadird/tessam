<?php

namespace App\Repository;

use App\Entity\StockMovementType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StockMovementType>
 *
 * @method StockMovementType|null find($id, $lockMode = null, $lockVersion = null)
 * @method StockMovementType|null findOneBy(array $criteria, array $orderBy = null)
 * @method StockMovementType[]    findAll()
 * @method StockMovementType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StockMovementTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StockMovementType::class);
    }

//    /**
//     * @return StockMovementType[] Returns an array of StockMovementType objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?StockMovementType
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

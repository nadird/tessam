<?php

namespace App\Repository;

use App\Entity\FeatureType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FeatureType>
 *
 * @method FeatureType|null find($id, $lockMode = null, $lockVersion = null)
 * @method FeatureType|null findOneBy(array $criteria, array $orderBy = null)
 * @method FeatureType[]    findAll()
 * @method FeatureType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeatureTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FeatureType::class);
    }

//    /**
//     * @return FeatureType[] Returns an array of FeatureType objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FeatureType
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

<?php

namespace App\Repository;

use App\Entity\ProductCombination;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductCombination>
 *
 * @method ProductCombination|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductCombination|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductCombination[]    findAll()
 * @method ProductCombination[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductCombinationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductCombination::class);
    }

//    /**
//     * @return ProductCombination[] Returns an array of ProductCombination objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ProductCombination
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

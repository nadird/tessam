<?php

namespace App\Repository;

use App\Entity\CustomerState;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CustomerState>
 *
 * @method CustomerState|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomerState|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomerState[]    findAll()
 * @method CustomerState[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerStateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerState::class);
    }

//    /**
//     * @return CustomerState[] Returns an array of CustomerState objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CustomerState
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

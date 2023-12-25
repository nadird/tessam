<?php

namespace App\Repository;

use App\Entity\WebJobApplication;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WebJobApplication>
 *
 * @method WebJobApplication|null find($id, $lockMode = null, $lockVersion = null)
 * @method WebJobApplication|null findOneBy(array $criteria, array $orderBy = null)
 * @method WebJobApplication[]    findAll()
 * @method WebJobApplication[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WebJobApplicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WebJobApplication::class);
    }

//    /**
//     * @return WebJobApplication[] Returns an array of WebJobApplication objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('w.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?WebJobApplication
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

<?php

namespace App\Repository;

use App\Entity\WebContactState;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WebContactState>
 *
 * @method WebContactState|null find($id, $lockMode = null, $lockVersion = null)
 * @method WebContactState|null findOneBy(array $criteria, array $orderBy = null)
 * @method WebContactState[]    findAll()
 * @method WebContactState[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WebContactStateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WebContactState::class);
    }

//    /**
//     * @return WebContactState[] Returns an array of WebContactState objects
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

//    public function findOneBySomeField($value): ?WebContactState
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

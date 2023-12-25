<?php

namespace App\Repository;

use App\Entity\EmplJobs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EmplJobs>
 *
 * @method EmplJobs|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmplJobs|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmplJobs[]    findAll()
 * @method EmplJobs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmplJobsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmplJobs::class);
    }

//    /**
//     * @return EmplJobs[] Returns an array of EmplJobs objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?EmplJobs
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

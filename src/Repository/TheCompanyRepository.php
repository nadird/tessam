<?php

namespace App\Repository;

use App\Entity\TheCompany;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TheCompany>
 *
 * @method TheCompany|null find($id, $lockMode = null, $lockVersion = null)
 * @method TheCompany|null findOneBy(array $criteria, array $orderBy = null)
 * @method TheCompany[]    findAll()
 * @method TheCompany[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TheCompanyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TheCompany::class);
    }

//    /**
//     * @return TheCompany[] Returns an array of TheCompany objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TheCompany
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

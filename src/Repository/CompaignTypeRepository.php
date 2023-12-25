<?php

namespace App\Repository;

use App\Entity\CompaignType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CompaignType>
 *
 * @method CompaignType|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompaignType|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompaignType[]    findAll()
 * @method CompaignType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompaignTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompaignType::class);
    }

//    /**
//     * @return CompaignType[] Returns an array of CompaignType objects
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

//    public function findOneBySomeField($value): ?CompaignType
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

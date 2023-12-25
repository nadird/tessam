<?php

namespace App\Repository;

use App\Entity\WebJobOffer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WebJobOffer>
 *
 * @method WebJobOffer|null find($id, $lockMode = null, $lockVersion = null)
 * @method WebJobOffer|null findOneBy(array $criteria, array $orderBy = null)
 * @method WebJobOffer[]    findAll()
 * @method WebJobOffer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WebJobOfferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WebJobOffer::class);
    }

//    /**
//     * @return WebJobOffer[] Returns an array of WebJobOffer objects
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

//    public function findOneBySomeField($value): ?WebJobOffer
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

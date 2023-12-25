<?php

namespace App\Repository;

use App\Entity\WebContact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WebContact>
 *
 * @method WebContact|null find($id, $lockMode = null, $lockVersion = null)
 * @method WebContact|null findOneBy(array $criteria, array $orderBy = null)
 * @method WebContact[]    findAll()
 * @method WebContact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WebContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WebContact::class);
    }

   /**
    * @return WebContact[] Returns an array of WebContact objects
     */
    public function findNonDeleted(): array
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.isDeleted = :val')
            ->setParameter('val', false)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?WebContact
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

<?php

namespace App\Repository;

use App\Entity\EmplRole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EmplRole>
 *
 * @method EmplRole|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmplRole|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmplRole[]    findAll()
 * @method EmplRole[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmplRoleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmplRole::class);
    }

//    /**
//     * @return EmplRole[] Returns an array of EmplRole objects
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

//    public function findOneBySomeField($value): ?EmplRole
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

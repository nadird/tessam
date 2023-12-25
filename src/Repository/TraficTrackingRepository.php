<?php

namespace App\Repository;

use App\Entity\TraficTracking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TraficTracking>
 *
 * @method TraficTracking|null find($id, $lockMode = null, $lockVersion = null)
 * @method TraficTracking|null findOneBy(array $criteria, array $orderBy = null)
 * @method TraficTracking[]    findAll()
 * @method TraficTracking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TraficTrackingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TraficTracking::class);
    }

	/**
	 * @return TraficTracking[]
	 */
	public function findNonDeleted(): array
	{
		return $this->createQueryBuilder('t')
			->andWhere('t.isDeleted = :val')
			->setParameter('val', false)
			->orderBy('t.id', 'ASC')
			->getQuery()
			->getResult()
			;
	}
//    /**
//     * @return TraficTracking[] Returns an array of TraficTracking objects
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

//    public function findOneBySomeField($value): ?TraficTracking
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

<?php

namespace App\Repository;

use App\Entity\Notification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Notification>
 *
 * @method Notification|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notification|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notification[]    findAll()
 * @method Notification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    /**
     * @return Notification[] Returns an array of Notification objects
     */
    public function findLastUnread($value): array
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.employee = :val')
            ->setParameter('val', $value)
	        ->andWhere('n.isProcessed = :val2')
	        ->setParameter('val2', false)
	        ->andWhere('n.isDeleted= :val3')
	        ->setParameter('val3', false)
            ->orderBy('n.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
	public function findLastRed($value): array
	{
		return $this->createQueryBuilder('n')
			->andWhere('n.employee = :val')
			->setParameter('val', $value)
			->andWhere('n.isProcessed = :val2')
			->setParameter('val2', true)
			->andWhere('n.isDeleted= :val3')
			->setParameter('val3', false)
			->orderBy('n.id', 'DESC')
			->setMaxResults(5)
			->getQuery()
			->getResult()
			;
	}
	public function findAllMyNotifications($value): array
	{
		return $this->createQueryBuilder('n')
			->andWhere('n.employee = :val')
			->setParameter('val', $value)
			->andWhere('n.isDeleted= :val3')
			->setParameter('val3', false)
			->orderBy('n.id', 'DESC')
			->getQuery()
			->getResult()
			;
	}
	

//    public function findOneBySomeField($value): ?Notification
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

<?php

namespace App\Repository;

use App\Entity\Document;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Document>
 *
 * @method Document|null find($id, $lockMode = null, $lockVersion = null)
 * @method Document|null findOneBy(array $criteria, array $orderBy = null)
 * @method Document[]    findAll()
 * @method Document[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Document::class);
    }
	

    /**
     * @return Document[] Returns an array of Document objects
     */
	public function findBySearch($q):array
	{
		return $this->createQueryBuilder('d')
			->andWhere('d.isDeleted = :val')
			->setParameter('val', false)
			->andWhere('d.name LIKE :val2')
			->setParameter('val2', '%'.$q.'%')
			->andWhere('d.url LIKE :val3')
			->setParameter('val3', '%'.$q.'%')
			->orderBy('d.id', 'DESC')
			->getQuery()
			->getResult()
			;
		
	}
   /*
    *  public function findContactNotDeleted($value): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.contact = :val')
            ->setParameter('val', $value)
	        ->andWhere('d.isDeleted = :val2')
	        ->setParameter('val2', false)
            ->orderBy('d.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
    */
	public function findLeadNotDeleted($value): array
	{
		return $this->createQueryBuilder('d')
			->andWhere('d.lead = :val')
			->setParameter('val', $value)
			->andWhere('d.isDeleted = :val2')
			->setParameter('val2', false)
			->orderBy('d.id', 'DESC')
			->getQuery()
			->getResult()
			;
	}
	public function findCustomerNotDeleted($value): array
	{
		return $this->createQueryBuilder('d')
			->andWhere('d.customer = :val')
			->setParameter('val', $value)
			->andWhere('d.isDeleted = :val2')
			->setParameter('val2', false)
			->orderBy('d.id', 'DESC')
			->getQuery()
			->getResult()
			;
	}
//    public function findOneBySomeField($value): ?Document
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

<?php

namespace App\Repository;

use App\Entity\BlogCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BlogCategory>
 *
 * @method BlogCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlogCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlogCategory[]    findAll()
 * @method BlogCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogCategory::class);
    }
	public function findAllActive(): array
	{
		return $this->createQueryBuilder('b')
			->andWhere('b.isActive = :val')
			->setParameter('val', true)
			->orderBy('b.id', 'ASC')
			->getQuery()
			->getResult()
			;
	}
	public function findAllActiveWithParent($parent): array
	{
		return $this->createQueryBuilder('b')
			->andWhere('b.isActive = :val')
			->setParameter('val', true)
			->andWhere('b.parentCategories = :parent')
			->setParameter('parent', $parent)
			->orderBy('b.id', 'ASC')
			->getQuery()
			->getResult()
			;
	}
	public function find3activeNotFirst()
	{
		return $this->createQueryBuilder('b')
			->andWhere('b.isActive = :val')
			->setParameter('val', true)
			->orderBy('b.id', 'ASC')
			->setMaxResults(3)
			->setFirstResult(1)
			->getQuery()
			->getResult()
			;
		
	}
	
//    /**
//     * @return BlogCategory[] Returns an array of BlogCategory objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?BlogCategory
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

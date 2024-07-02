<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function findAllDesc(): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.id', 'DESC')  // Assuming you want to sort by ID. Change 'id' to the desired field.
            ->getQuery()
            ->getResult();
    }

    public function countAllCategories(): int
    {
        return (int) $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findBySearchTerm(?string $searchTerm): array
    {
        $qb = $this->createQueryBuilder('c');

        if ($searchTerm) {
            $qb->andWhere('c.name LIKE :searchTerm')
               ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }

        return $qb->orderBy('c.name', 'ASC')
                  ->getQuery()
                  ->getResult();
    }
    //    /**
    //     * @return Category[] Returns an array of Category objects
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

    //    public function findOneBySomeField($value): ?Category
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

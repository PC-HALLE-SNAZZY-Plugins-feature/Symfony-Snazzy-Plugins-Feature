<?php

namespace App\Repository;

use App\Entity\Plugin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Plugin>
 */
class PluginRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Plugin::class);
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
    //     * @return Plugin[] Returns an array of Plugin objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Plugin
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

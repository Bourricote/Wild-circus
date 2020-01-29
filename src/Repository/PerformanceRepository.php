<?php

namespace App\Repository;

use App\Entity\Performance;
use App\Entity\PerformanceSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Performance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Performance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Performance[]    findAll()
 * @method Performance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PerformanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Performance::class);
    }

    public function searchPerformance(PerformanceSearch $search)
    {
        $query = $this->findAllQuery();

        if ($search->getName()) {
            $query = $query
                ->andwhere('p.name LIKE :val')
                ->setParameter('val', '%' . $search->getName() . '%');
        }

        if ($search->getCategory()) {
            $query = $query
                ->join('p.category', 'c')
                ->andwhere('c.name = :category')
                ->setParameter('category', $search->getCategory()->getName());
        }

        return $query->getQuery()->getResult();
    }

    private function findAllQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('p');
    }

    // /**
    //  * @return Performance[] Returns an array of Performance objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Performance
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

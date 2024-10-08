<?php

namespace App\Repository;

use App\Entity\SousCategorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SousCategorie>
 */
class SousCategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SousCategorie::class);
    }
    public function findAlphabeticallyOrdered()
    {
        return $this->createQueryBuilder('sc')
            ->orderBy('sc.description', 'ASC');
    }
       /**
        * @return SousCategorie[] Returns an array of SousCategorie objects
        */
       public function findOneById(int $value): ?SousCategorie
       {
           return $this->createQueryBuilder('s')
               ->andWhere('s.id = :val')
               ->setParameter('val', $value)
               ->getQuery()
               ->getOneOrNullResult();
       }
       
    //    /**
    //     * @return SousCategorie[] Returns an array of SousCategorie objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?SousCategorie
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

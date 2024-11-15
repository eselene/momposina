<?php

namespace App\Repository;

use App\Entity\Evenement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Evenement>
 */
class EvenementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenement::class);
    }
   /**
    * @return Evenement[] Returns an array of Evenement objects
    */
    public function findAllOrderByDate(): array
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.date', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();             
    }
          

   /**
    * @return Evenement[] Returns an array of Evenement objects
    */
    public function findAllOrderByDateVisible(): array
    {
        return $this->createQueryBuilder('e')
            ->select('e.id, e.titre, e.description, e.date, e.plageHeure, e.lieu, e.prix, e.photo1, e.visibleWeb')
            ->andWhere('e.visibleWeb = :visible')
            ->setParameter('visible', true)
            ->orderBy('e.date', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
//    /**
//     * @return Evenement[] Returns an array of Evenement objects
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

//    public function findOneBySomeField($value): ?Evenement
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

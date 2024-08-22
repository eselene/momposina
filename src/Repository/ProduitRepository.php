<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

  /*** @return Produit[] Returns an array of Produit objects   */
  public function findByCategorie($idCategorie): array
  {
      return $this->createQueryBuilder('p')
          ->join('p.sousCategorie', 's')
          ->join('s.categorie', 'c')
          ->andWhere('c.id = :val')
          ->setParameter('val', $idCategorie)
          ->orderBy('p.nom', 'ASC')
          ->getQuery()
          ->getResult();
  }
  

  /*** @return Produit[] Returns an array of Produit objects   */
  public function findById($id): array
  {
      return $this->createQueryBuilder('p')
          ->andWhere('p.id = :val')
          ->setParameter('val', $id)
          ->orderBy('p.nom', 'ASC')
          ->setMaxResults(10)
          ->getQuery()
          ->getResult()
      ;
  }
  /*** @return Produit[] Returns an array of Produit objects   */
  public function findByNom($id): array
  {
      return $this->createQueryBuilder('p')
          ->andWhere('p.id = :val')
          ->setParameter('val', $id)
          ->orderBy('p.nom', 'ASC')
          ->setMaxResults(10)
          ->getQuery()
          ->getResult()
      ;
  }
   public function findSomesByNomNomEs($value): ?Produit
   {
       return $this->createQueryBuilder('p')
           ->andWhere('p.nom = :val')
           ->setParameter('val', $value[0])
           ->andWhere('p.nomEs = :val2')
           ->setParameter('val2', $value[1])           
           ->getQuery()
           ->getOneOrNullResult()
       ;
   }

//    /**
//     * @return Produit[] Returns an array of Produit objects
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

//    public function findOneBySomeField($value): ?Produit
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

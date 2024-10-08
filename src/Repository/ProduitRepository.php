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
            ->andWhere('p.visibleWeb = true')
            ->setParameter('val', $idCategorie)
            ->orderBy('p.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // Récupére les produits associés à une sous-catégorie spécifique.
    /*** @return Produit[] Returns an array of Produit objects   */
    public function findBySousCategorieId($sousCategorieId): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.sousCategorie = :val')
            ->andWhere('p.visibleWeb = true')
            ->setParameter('val', $sousCategorieId)
            ->orderBy('p.nom', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*** @return Produit[] Returns an array of Produit objects   */
    public function findById($id): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.id = :val')
            // ->andWhere('p.visibleWeb = true')
            ->setParameter('val', $id)
            ->orderBy('p.nom', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /*** @return Produit[] Returns an array of Produit objects   */
    public function findByNom($value): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.nom = :val')
            ->andWhere('p.visibleWeb = true')
            ->setParameter('val', $value)
            ->orderBy('p.nom', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByNomNomEs($value, $valSousCategorieId): array
    {
        try {
            return $this->createQueryBuilder('p')
                ->andWhere('p.nom LIKE :val OR p.nomEs LIKE :val')
                ->andWhere('p.sousCategorie = :valSousCategorieId')
                ->andWhere('p.visibleWeb = true')
                ->setParameter('val', '%' . $value . '%')
                ->setParameter('valSousCategorieId', $valSousCategorieId)
                ->getQuery()
                ->getResult();
        } catch (\Exception $e) {
            $this->getEntityManager()->getConnection()->getConfiguration();
            throw $e; // Relancer l'exception pour qu'elle soit gérée par le contrôleur
        }
    }

    public function findAllOrderByName(): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.nom', 'ASC') // 'ASC' pour un tri croissant, 'DESC' pour un tri décroissant
            ->getQuery()
            ->getResult();
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

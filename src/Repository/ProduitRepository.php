<?php
// src/Repository/ProduitRepository.php
namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
// use Psr\Log\LoggerInterface;

/**
 * @extends ServiceEntityRepository<Produit>
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
        // $this->logger = $logger;
    }

    /** 
     * @return Produit[] Returns an array of Produit objects
     */
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

    /** 
     * @return Produit[] Returns an array of Produit objects
     */
    public function findBySousCategorieId($sousCategorieId): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.sousCategorie = :val')
            ->andWhere('p.visibleWeb = true')
            ->setParameter('val', $sousCategorieId)
            ->orderBy('p.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /** 
     * @return Produit[] Returns an array of Produit objects
     */
    public function findById($id): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.id = :val')
            ->setParameter('val', $id)
            ->orderBy('p.nom', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /** 
     * @return Produit[] Returns an array of Produit objects
     */
    public function findByNom($value): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.nom = :val')
            ->andWhere('p.visibleWeb = true')
            ->setParameter('val', $value)
            ->orderBy('p.nom', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Produit[] Returns an array of Produit objects
     */
    public function findByNomNomEsFR($value, int $sousCategorieId): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('LOWER(p.nom) LIKE :val OR LOWER(p.nomEs) LIKE :val')
            ->andWhere('p.sousCategorie = :sousCategorieId')
            ->andWhere('p.visibleWeb = true')
            ->setParameter('val', '%' . strtolower($value) . '%')
            ->setParameter('sousCategorieId', $sousCategorieId)
            ->orderBy('p.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Produit[] Returns an array of Produit objects
     */
    public function findAllOrderByName(): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }
}

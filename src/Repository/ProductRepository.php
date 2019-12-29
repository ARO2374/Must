<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Le repository de gestion des produits.
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Enregistre un produit en base (création ou modification).
     * @param $product Le produit à créer/mettre à jour.
     * @return Le produit ({@link App\Entity\Product}) mise à jour.
     */
    public function save(Product $product): Product
    {
        $this->getEntityManager()->persist($product);
        $this->getEntityManager()->flush();

        return $product;
    }

    /**
     * Supprime un produit en base.
     * @param $product Le produit à supprimer.
     */
    public function delete(Product $product): void
    {
        $this->getEntityManager()->remove($product);
        $this->getEntityManager()->flush();
    }
}

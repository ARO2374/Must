<?php

namespace App\Repository;

use App\Entity\ProductsCategories;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Le repository de gestion des ProductsCategories.
 */
class ProductsCategoriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductsCategories::class);
    }

    /**
     * Supprime un ProductsCategories en base.
     * @param $pc Le ProductsCategories à supprimer.
     */
    public function delete(ProductsCategories $pc): void
    {
        $this->getEntityManager()->remove($pc);
        $this->getEntityManager()->flush();
    }

    /**
     * Permet de savoir si l'association existe déjà en base ou non..
     * @param $productId L'id du produit.
     * @param $categoryId L'id de la catégorie.
     * @return Un booléen pour savoir si l'association existe déjà en base ou non..
     */
    public function existsByProductAndCategory(int $productId, int $categoryId): bool
    {
        return 0 < $this->createQueryBuilder('pc')
            ->select('count(pc.id)')
            ->andWhere('pc.product = :productId')
            ->andWhere('pc.category = :categoryId')
            ->setParameter('productId', $productId)
            ->setParameter('categoryId', $categoryId)
            ->getQuery()
            ->getSingleScalarResult();
    }
}

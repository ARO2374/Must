<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Le repository de gestion des catégories.
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * Enregistre une catégorie en base (création ou modification).
     * @param $category La catégorie à créer/mettre à jour.
     * @return La catégorie ({@link App\Entity\Category}) mise à jour.
     */
    public function save(Category $category): Category
    {
        $this->getEntityManager()->persist($category);
        $this->getEntityManager()->flush();

        return $category;
    }

    /**
     * Supprime une catégorie en base.
     * @param $category La catégorie à supprimer.
     */
    public function delete(Category $category): void
    {
        $this->getEntityManager()->remove($category);
        $this->getEntityManager()->flush();
    }
}

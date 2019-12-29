<?php

namespace App\Repository;

use App\Entity\Brand;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Le repository de gestion des marques.
 */
class BrandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Brand::class);
    }

    /**
     * Enregistre une marque en base (création ou modification).
     * @param $brand La marque à créer/mettre à jour.
     * @return La marque ({@link App\Entity\Brand}) mise à jour.
     */
    public function save(Brand $brand): Brand
    {
        $this->getEntityManager()->persist($brand);
        $this->getEntityManager()->flush();

        return $brand;
    }

    /**
     * Supprime une marque en base.
     * @param $brand La marque à supprimer.
     */
    public function delete(Brand $brand): void
    {
        $this->getEntityManager()->remove($brand);
        $this->getEntityManager()->flush();
    }
}

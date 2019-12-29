<?php
namespace App\Service;

use App\Repository\ProductsCategoriesRepository;
use App\Entity\ProductsCategories;
use Doctrine\ORM\EntityNotFoundException;

/**
 * Service de gestion de l'association Product/Category.
 */
class ProductsCategoriesService
{
    /**
     * @var ProductsCategoriesRepository $productsCategoriesRepository
     */
    private $productsCategoriesRepository;

    /**
     * Constructeur.
     * @param $brandRepository Le repository de gestion des marques.
     */
    public function __construct(ProductsCategoriesRepository $productsCategoriesRepository){
        $this->productsCategoriesRepository = $productsCategoriesRepository;
    }

    /**
     * Permet de savoir si l'association existe déjà en base ou non..
     * @param $productId L'id du produit.
     * @param $categoryId L'id de la catégorie.
     * @return Un booléen pour savoir si l'association existe déjà en base ou non..
     */
    public function existsByProductAndCategory(int $productId, int $categoryId): bool
    {
        return $this->productsCategoriesRepository->existsByProductAndCategory($productId, $categoryId);
    }

    /**
     * Supprime un ProductsCategories en base.
     * @param $pc Le ProductsCategories à supprimer.
     */
    public function deleteProductsCategories(ProductsCategories $pc): void
    {
        $this->productsCategoriesRepository->delete($pc);
    }
}
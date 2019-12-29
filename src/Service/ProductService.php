<?php
namespace App\Service;

use App\Repository\ProductRepository;
use App\Entity\Product;
use App\Entity\ProductsCategories;
use Doctrine\ORM\EntityNotFoundException;

/**
 * Service de gestion des produits.
 */
class ProductService
{
    /**
     * @var ProductRepository $productRepository
     */
    private $productRepository;
    private $productsCategoriesService;

    /**
     * Constructeur.
     * @param $productRepository Le repository de gestion des produits.
     */
    public function __construct(ProductRepository $productRepository, ProductsCategoriesService $productsCategoriesService){
        $this->productRepository = $productRepository;
        $this->productsCategoriesService = $productsCategoriesService;
    }

    /**
     * Retourne la liste de tous les produits en base.
     * @return Une liste de produits ({@link App\Entity\Product}).
     */
    public function getProducts(): array
    {
        return $this->productRepository->findAll();
    }

    /**
     * Retourne le produit correspondant à l'id passé en paramètre.
     * @param $productId L'id du produit à mettre à jour.
     * @throws EntityNotFoundException Si l'id passé en paramètre ne correspond à aucune des entités présentes en base.
     * @return Un produit.
     */
    public function getProduct(int $productId): Product
    {
        $productBD = $this->productRepository->find($productId);
        if (!$productBD) {
            throw new EntityNotFoundException($productId);
        }
        
        return $productBD;
    }

    /**
     * Ajoute un produit en base.
     * @param $product La produit à ajouter.
     * @return Un objet {@link App\Entity\Product}.
     */
    public function addProduct(Product $product): Product
    {
        return $this->productRepository->save($product);
    }

    /**
     * Supprime un produit en base.
     * @param $productId L'id du produit à supprimer.
     * @throws EntityNotFoundException Si l'id passé en paramètre ne correspond à aucune des entités présentes en base.
     */
    public function deleteProduct(int $productId): void
    {
        $product = $this->productRepository->findOneById($productId);
        if (!$product) {
            throw new EntityNotFoundException($productId);
        }
        $this->productRepository->delete($product);
    }

    /**
     * Met à jour un produit en base (uniquement la propriété "name").
     * @param $productId L'id du produit à mettre à jour.
     * @param $productDTO Un objet {@link App\Entity\Product} contenant toutes les nouvelles valeurs de l'objet (au minimum la propriété "name").
     * @throws EntityNotFoundException Si l'id passé en paramètre ne correspond à aucune des entités présentes en base.
     * @return La produit mise à jour.
     */
    public function updateProduct(int $productId, Product $productDTO): Product
    {
        $productBD = $this->productRepository->findOneById($productId);
        if (!$productBD) {
            throw new EntityNotFoundException($productId);
        }
        
        $productBD->setName($productDTO->getName());
        $productBD->setDescription($productDTO->getDescription());
        $productBD->setUrl($productDTO->getUrl());
        $productBD->setBrand($productDTO->getBrand());

        // Suppression des ProductsCategories qui ne sont plus dans la liste
        // Extraction de la liste des ids des catégories liées à ce produit (plus facile à manipuler ensuite)
        $idsCategoriesDTO = array_map(function(ProductsCategories $pcDTO) {
            return $pcDTO->getCategory()->getId();
        }, $productDTO->getProductsCategories()->toArray());

        foreach ($productBD->getProductsCategories() as $pc) {
            // Suppression individuelle pour ne pas supprimer et recréer une association déjà correcte (évite également l'incrément des ids en base)
            if (!in_array($pc->getCategory()->getId(), $idsCategoriesDTO)) {
                $this->productsCategoriesService->deleteProductsCategories($pc);
            }
        }

        // Ajout des nouvelles associations
        foreach ($productDTO->getProductsCategories() as $pc) {
            $assoExistante = $this->productsCategoriesService->existsByProductAndCategory($productBD->getId(), $pc->getCategory()->getId());

            if (!$assoExistante) {
                $productBD->addProductsCategories($pc);
            }
        }

        return $this->productRepository->save($productBD);
    }
}
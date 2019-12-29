<?php
namespace App\Controller;

use App\Service\ProductService;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\View;
use JMS\Serializer\Annotation\PostDeserialize;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use JMS\Serializer\SerializerInterface;

/**
 * Controller de gestion des produits.
 * @Route("/api/products")
 */
class ProductController extends AbstractFOSRestController
{
	/**
     * @var $productService
     */
    private $productService;

    /**
     * Constructeur de l'ApiController.
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Retourne la liste de tous les produits en base.
     * @return Une liste de produits ({@link App\Entity\Product}).
     * 
     * @Get(path = "")
     * @View
     */
    public function getProductsAction(): array
    {
        return $this->productService->getProducts();
    }

    /**
     * Retourne le produit correspondant à l'id passé en paramètre.
     * @throws EntityNotFoundException Si l'id passé en paramètre ne correspond à aucune des entités présentes en base.
     * @return Un produit ({@link App\Entity\Product}).
     * 
     * @Get(path = "/{productId}")
     * @View
     */
    public function getProductAction(int $productId): Product
    {
        return $this->productService->getProduct($productId);
    }


    /**
     * Ajoute un produit en base.
     * @param $product Le produit à ajouter.
     * @return Un objet {@link App\Entity\Product}.
     * 
     * @Post("")
     * @View
     * @ParamConverter("product", class="App\Entity\Product", converter="fos_rest.request_body")
     */
    public function createProductAction(Request $request, Product $product): Product
    {
        return $this->productService->addProduct($product);
    }

    /**
     * Supprime un produit en base.
     * @param $productId L'id du produit à supprimer.
     * @throws EntityNotFoundException Si l'id passé en paramètre ne correspond à aucune des entités présentes en base.
     * 
     * @Delete("/{productId}")
     * @View
     */
    public function deleteProductAction(int $productId)
    {
        $this->productService->deleteProduct($productId);
    }

    /**
     * Met à jour un produit en base (uniquement la propriété "name").
     * @param $productId L'id du produit à mettre à jour.
     * @param $productDTO Un objet {@link App\Entity\Product} contenant toutes les nouvelles valeurs de l'objet (au minimum la propriété "name").
     * @throws EntityNotFoundException Si l'id passé en paramètre ne correspond à aucune des entités présentes en base.
     * @return Le produit mise à jour.
     * 
     * @Put("/{productId}")
     * @View
     * @ParamConverter("productDTO", class="App\Entity\Product", converter="fos_rest.request_body")
     */
    public function updateProductAction(int $productId, Product $productDTO): Product
    {
        return $this->productService->updateProduct($productId, $productDTO);
    }
}
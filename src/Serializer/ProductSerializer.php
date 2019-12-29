<?php

namespace App\Serializer;

use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\JsonDeserializationVisitor;
use JMS\Serializer\Context;
use App\Entity\Product;
use App\Entity\ProductsCategories;
use App\Entity\Category;
use App\Entity\Brand;
use App\Service\BrandService;
use App\Service\CategoryService;
use App\Service\ProductsCategoriesService;

/**
 * Serializer des produits.
 */
class ProductSerializer implements SubscribingHandlerInterface
{
    /**
     * @var $brandRepository Le service de gestion des marques.
     */
    private $brandService;

    /**
     * @var $categoryService Le service de gestion des catégories.
     */
    private $categoryService;

    /**
     * @var $productsCategoriesService Le service de gestion des ProductsCategories.
     */
    private $productsCategoriesService;

    /**
     * Constructeur de l'ApiController.
     * @param BrandService $brandService Le service de gestion des marques.
     * @param CategoryService $categoryService Le service de gestion des catégories.
     * @param ProductsCategoriesService $productsCategoriesService Le service de gestion des ProductsCategories.
     */
    public function __construct(BrandService $brandService, CategoryService $categoryService, ProductsCategoriesService $productsCategoriesService)
    {
        $this->brandService = $brandService;
        $this->categoryService = $categoryService;
        $this->productsCategoriesService = $productsCategoriesService;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribingMethods()
    {
        return [
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => Product::class,
                'method' => 'serialize',
            ],
            [
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'format' => 'json',
                'type' => Product::class,
                'method' => 'deserialize',
            ],
        ];
    }

    /**
     * Méthode permettant de convertir un objet Product en json
     * @param JsonSerializationVisitor $visitor
     * @param Product                  $product
     * @param array                    $type
     * @param Context                  $context
     *
     * @return array
     */
    public function serialize(JsonSerializationVisitor $visitor, Product $product, array $type, Context $context)
    {
        $data = [
            'name' => $product->getName(),
            'description' => $product->getDescription(),
            'brand' => $product->getBrand(),
            'categories' => array_map(
                function (ProductsCategories $pc) {
                    return $pc->getCategory();
                },
                $product->getProductsCategories()->toArray()
            ),
            'url' => $product->getUrl(),
            'id' => $product->getId(),
            'md5' => md5($product->getId())
        ];

        return $visitor->visitArray($data, $type, $context);
    }

    /**
     * Méthode permettant de construire un objet Product à partir d'un json.
     * @param JsonDeserializationVisitor $visitor
     * @param array                      $productDTO
     * @param array                      $type
     * @param Context                    $context
     *
     * @return Product
     */
    public function deserialize(JsonDeserializationVisitor $visitor, array $productDTO, array $type, Context $context)
    {
        $product = new Product();
        if (array_key_exists('name', $productDTO)) {
            $product->setName($productDTO['name']);
        }
        $product->setActive(true);
        if (array_key_exists('description', $productDTO)) {
            $product->setDescription($productDTO['description']);
        }
        if (array_key_exists('url', $productDTO)) {
            $product->setUrl($productDTO['url']);
        }
        if (array_key_exists('brand', $productDTO)) {
            $product->setBrand($this->brandService->getBrand(intval($productDTO['brand'])));
        }
        if (array_key_exists('categories', $productDTO)) {
            foreach ($productDTO['categories'] as $key => $value) {
                $pc = new ProductsCategories();
                $pc->setCategory($this->categoryService->getCategory(intval($value)));

                $product->addProductsCategories($pc);
            }
        }

        return $product;
    }
}

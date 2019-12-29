<?php
namespace App\Controller;

use App\Service\BrandService;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\View;
use App\Entity\Brand;
use Doctrine\ORM\EntityNotFoundException;

/**
 * @Route("/api/brands")
 */
class BrandController extends FOSRestController
{
	/**
     * @var $brandService
     */
    private $brandService;

    /**
     * Constructeur de l'ApiController.
     * @param BrandService $brandService
     */
    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }

    /**
     * Retourne la liste de toutes les marques en base.
     * @return Une liste de marques ({@link App\Entity\Brand}).
     * 
     * @Get(path = "")
     * @View
     */
    public function getBrandsAction(): array
    {
        return $this->brandService->getBrands();
    }


    /**
     * Ajoute une marque en base.
     * @param $brand La marque à ajouter.
     * @return Un objet {@link App\Entity\Brand}.
     * 
     * @Post("")
     * @View
     * @ParamConverter("brand", class="App\Entity\Brand", converter="fos_rest.request_body")
     */
    public function createBrandAction(Brand $brand): Brand
    {
        return $this->brandService->addBrand($brand);
    }

    /**
     * Supprime une marque en base.
     * @param $brandId L'id de la marque à supprimer.
     * @throws EntityNotFoundException
     * 
     * @Delete("/{brandId}")
     * @View
     */
    public function deleteBrandAction(int $brandId)
    {
        $this->brandService->deleteBrand($brandId);
    }

    /**
     * Met à jour une marque en base (uniquement la propriété "name").
     * @param $brandId L'id de la marque à mettre à jour.
     * @param $brandDTO Un objet {@link App\Entity\Brand} contenant toutes les nouvelles valeurs de l'objet (au minimum la propriété "name").
     * @return La marque mise à jour.
     * 
     * @Put("/{brandId}")
     * @View
     * @ParamConverter("brand", class="App\Entity\Brand", converter="fos_rest.request_body")
     */
    public function updateBrandAction(int $brandId, Brand $brand): Brand
    {
        return $this->brandService->updateBrand($brandId, $brand);
    }
}
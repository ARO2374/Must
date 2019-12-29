<?php
namespace App\Service;

use App\Repository\BrandRepository;
use App\Entity\Brand;
use Doctrine\ORM\EntityNotFoundException;

/**
 * Service de gestion des marques.
 */
class BrandService
{
    /**
     * @var BrandRepository $brandRepository
     */
    private $brandRepository;

    /**
     * Constructeur.
     * @param $brandRepository Le repository de gestion des marques.
     */
    public function __construct(BrandRepository $brandRepository){
        $this->brandRepository = $brandRepository;
    }

    /**
     * Retourne la liste de toutes les marques en base.
     * @return Une liste de marques ({@link App\Entity\Brand}).
     */
    public function getBrands(): array
    {
        return $this->brandRepository->findAll();
    }

    /**
     * Retourne une marque en base.
     * @return Une marque ({@link App\Entity\Brand}).
     */
    public function getBrand($id): Brand
    {
        return $this->brandRepository->find($id);
    }

    /**
     * Ajoute une marque en base.
     * @param $brand La marque à ajouter.
     * @return Un objet {@link App\Entity\Brand}.
     */
    public function addBrand(Brand $brand): Brand
    {
        return $this->brandRepository->save($brand);
    }

    /**
     * Supprime une marque en base.
     * @param $brandId L'id de la marque à supprimer.
     * @throws EntityNotFoundException Si l'id passé en paramètre ne correspond à aucune des entités présentes en base.
     */
    public function deleteBrand(int $brandId): void
    {
        $brand = $this->brandRepository->findOneById($brandId);
        if (!$brand) {
            throw new EntityNotFoundException($brandId);
        }
        $this->brandRepository->delete($brand);
    }

    /**
     * Met à jour une marque en base (uniquement la propriété "name").
     * @param $brandId L'id de la marque à mettre à jour.
     * @param $brandDTO Un objet {@link App\Entity\Brand} contenant toutes les nouvelles valeurs de l'objet (au minimum la propriété "name").
     * @throws EntityNotFoundException Si l'id passé en paramètre ne correspond à aucune des entités présentes en base.
     * @return La marque mise à jour.
     */
    public function updateBrand(int $brandId, Brand $brandDTO): Brand
    {
        $brandBD = $this->brandRepository->findOneById($brandId);
        if (!$brandBD) {
            throw new EntityNotFoundException($brandId);
        }
        
        $brandBD->setName($brandDTO->getName());

        return $this->brandRepository->save($brandBD);
    }
}
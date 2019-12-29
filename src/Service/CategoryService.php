<?php
namespace App\Service;

use App\Repository\CategoryRepository;
use App\Entity\Category;
use Doctrine\ORM\EntityNotFoundException;

/**
 * Service de gestion des catégories.
 */
class CategoryService
{
    /**
     * @var CategoryRepository $categoryRepository
     */
    private $categoryRepository;

    /**
     * Constructeur.
     * @param $categoryRepository Le repository de gestion des catégories.
     */
    public function __construct(CategoryRepository $categoryRepository){
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Retourne la liste de toutes les catégories en base.
     * @return Une liste de catégories ({@link App\Entity\Category}).
     */
    public function getCategories(): array
    {
        return $this->categoryRepository->findAll();
    }

    /**
     * Retourne la catégorie en base.
     * @return Une catégorie ({@link App\Entity\Category}).
     */
    public function getCategory($id): Category
    {
        return $this->categoryRepository->find($id);
    }

    /**
     * Ajoute une catégorie en base.
     * @param $category La catégorie à ajouter.
     * @return Un objet {@link App\Entity\Category}.
     */
    public function addCategory(Category $category): Category
    {
        return $this->categoryRepository->save($category);
    }

    /**
     * Supprime une catégorie en base.
     * @param $categoryId L'id de la catégorie à supprimer.
     * @throws EntityNotFoundException Si l'id passé en paramètre ne correspond à aucune des entités présentes en base.
     */
    public function deleteCategory(int $categoryId): void
    {
        $category = $this->categoryRepository->findOneById($categoryId);
        if (!$category) {
            throw new EntityNotFoundException($categoryId);
        }

        $this->categoryRepository->delete($category);
    }

    /**
     * Met à jour une catégorie en base (uniquement la propriété "name").
     * @param $categoryId L'id de la catégorie à mettre à jour.
     * @param $categoryDTO Un objet {@link App\Entity\Category} contenant toutes les nouvelles valeurs de l'objet (au minimum la propriété "name").
     * @throws EntityNotFoundException Si l'id passé en paramètre ne correspond à aucune des entités présentes en base.
     * @return La catégorie mise à jour.
     */
    public function updateCategory(int $categoryId, Category $categoryDTO): Category
    {
        $categoryBD = $this->categoryRepository->findOneById($categoryId);
        if (!$categoryBD) {
            throw new EntityNotFoundException($categoryId);
        }

        $categoryBD->setName($categoryDTO->getName());

        return $this->categoryRepository->save($categoryBD);
    }
}
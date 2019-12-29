<?php
namespace App\Controller;

use App\Service\CategoryService;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\View;
use App\Entity\Category;

/**
 * Controller de gestion des catégories.
 * @Route("/api/categories")
 */
class CategoryController extends FOSRestController
{
	/**
     * @var $categoryService
     */
    private $categoryService;

    /**
     * Constructeur de l'ApiController.
     * @param CategoryService $categoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Retourne la liste de toutes les catégories en base.
     * @return Une liste de catégories ({@link App\Entity\Category}).
     * 
     * @Get(path = "")
     * @View
     */
    public function getCategoriesAction(): array
    {
        return $this->categoryService->getCategories();
    }


    /**
     * Ajoute une catégorie en base.
     * @param $category La catégorie à ajouter.
     * @return Un objet {@link App\Entity\Category}.
     * 
     * @Post("")
     * @View
     * @ParamConverter("category", class="App\Entity\Category", converter="fos_rest.request_body")
     */
    public function createCategoryAction(Category $category): Category
    {
        return $this->categoryService->addCategory($category);
    }

    /**
     * Supprime une catégorie en base.
     * @param $categoryId L'id de la catégorie à supprimer.
     * @throws EntityNotFoundException Si l'id passé en paramètre ne correspond à aucune des entités présentes en base.
     * 
     * @Delete("/{categoryId}")
     * @View
     */
    public function deleteCategoryAction(int $categoryId)
    {
        $this->categoryService->deleteCategory($categoryId);
    }

    /**
     * Met à jour une catégorie en base (uniquement la propriété "name").
     * @param $categoryId L'id de la catégorie à mettre à jour.
     * @param $categoryDTO Un objet {@link App\Entity\Category} contenant toutes les nouvelles valeurs de l'objet (au minimum la propriété "name").
     * @throws EntityNotFoundException Si l'id passé en paramètre ne correspond à aucune des entités présentes en base.
     * @return La catégorie mise à jour.
     * 
     * @Put("/{categoryId}")
     * @View
     * @ParamConverter("categoryDTO", class="App\Entity\Category", converter="fos_rest.request_body")
     */
    public function updateCategoryAction(int $categoryId, Category $categoryDTO): Category
    {
        return $this->categoryService->updateCategory($categoryId, $categoryDTO);
    }
}
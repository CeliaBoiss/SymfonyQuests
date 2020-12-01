<?php
// src/Controller/CategoryController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use App\Repository\ProgramRepository;

/**
* @Route("/categories", name="category_")
*/
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @return Response a response instance
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        return $this->render('category/index.html.twig', ['categories' => $categories]);
    }

/**
 * @Route("/{categoryName}", name="show")
 * @return Response
 */
    public function show(string $categoryName, CategoryRepository $categoryRepository, ProgramRepository $programRepository): Response
    {
        $category = $categoryRepository->findBy(['name' => $categoryName]);

        if (!$category) {
            throw $this->createNotFoundException('No category named : ' . $categoryName);
        }

        $programs = $programRepository->findBy(['category' => $category], ['id' => 'DESC'], 3);

        return $this->render('category/show.html.twig', ['programs' => $programs]);
    }
}
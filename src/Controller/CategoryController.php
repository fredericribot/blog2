<?php
namespace App\Controller;
use App\Entity\Article;
use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
/**
 * @Route("/category", name="category_")
 */
class CategoryController extends AbstractController
{
    /**
     * Getting all the articles of a category by name
     *
     * @Route("/show/{name<^[a-z0-9-]+$>}", name="show")
     */
    public function showByCategory(Category $category): Response
    {
        // $category = $this->getDoctrine()
        //     ->getRepository(Category::class)
        //     ->findOneBy(['name' => mb_strtolower($categoryName)]);
        // $articles = $this->getDoctrine()
        //     ->getRepository(Article::class)
        //     ->findBy(['category' => $category], ['id' => 'DESC'], 3);  
        $articles = $category->getArticles();
        return $this->render(
            'blog/category/list.html.twig',
            [
                'category' => $category,
                'articles' => $articles,
            ]
        );
    }
    /**
     * Add Category
     *
     * @Route ("/add", name = "add")
     */
    public function addCategory(request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();
            echo '<pre>', var_dump($category), '</pre>';
            $entityManager->persist($category);
            $entityManager->flush();
            return $this->redirectToRoute('category_show', ['name' => strtolower($category->getName())]);
        }
        return $this->render(
            'blog/category/add.html.twig',
            [
                'category' => $category,
                'form' => $form->createView(),
            ]
        );
    }
}
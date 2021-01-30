<?php

namespace App\Controller;

use App\Entity\Category;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/{slug}", name="home_category")
     */
    public function index(Category $category, PaginatorInterface $paginator, Request $request)
    {
        $page = $request->query->getInt('page', 1);

        $productsPaginated = $paginator->paginate($category->getProducts(), $page, 3);
        $category = $category->getName();

        return $this->render('category.html.twig', compact('category','productsPaginated'));
    }
}

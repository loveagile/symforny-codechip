<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="home_search", priority="10")
     */
    public function index(Request $request, ProductRepository  $productRepository, PaginatorInterface $paginator)
    {
        $search = $request->query->get('s');
        $page = $request->query->getInt('page', 1);

        $products = $productRepository->searchProducts($search);
        $products = $paginator->paginate($products, $page);

        return $this->render('search.html.twig', compact('search', 'products'));
    }
}

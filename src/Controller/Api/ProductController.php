<?php

namespace App\Controller\Api;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/api/products", name="api_products")
     */
    public function index(ProductRepository $productRepository)
    {
        $products = $productRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->json($products, 200, [], [
            'groups' => ['productList']
        ]);
    }
}

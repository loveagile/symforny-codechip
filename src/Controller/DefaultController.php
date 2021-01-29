<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\{User, Address, Order};

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index( ProductRepository $productRepository)
    {
        $products = $productRepository->findAll();
        dump($products);
        return $this->render('index.html.twig', compact('products'));
    }

    /**
     * @Route("/{slug}", name="product_single")
     */
    public function product(Product $product)
    {
        return $this->render('single.html.twig', compact('product'));
    }
}

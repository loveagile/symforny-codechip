<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(/*, SerializerInterface $serializer*/)
    {
        //$products = $serializer->serialize($products, 'json', ['groups' =>['productList']]);

        return $this->render('index.html.twig');
    }

    /**
     * @Route("/{slug}", name="product_single")
     */
    public function product(Product $product)
    {
        return $this->render('single.html.twig', compact('product'));
    }
}

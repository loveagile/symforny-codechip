<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index(): Response
    {
        $name = 'Lwcyano Will';



        //Remoção de dados com Doctrine


        //Buscando todos os produtos
        //$products = $this->getDoctrine()->getRepository(Product::class)->findAll();
        //dump($products);
        //Buscando um produto especifico
        //$product = $this->getDoctrine()->getRepository(Product::class)->find(2);
        //print $product->getName();
        //dump($product);
        //Buscando um produto via slug
        $product = $this->getDoctrine()->getRepository(Product::class)->findBy(['id'=>2]);
        dump($product);
        return $this->render('index.html.twig', compact('name'));
    }

    /**
     * @Route("/product/{slug}", name="product_single")
     */
    public function product($slug): Response
    {
        return $this->render('single.html.twig', compact('slug'));
    }
}

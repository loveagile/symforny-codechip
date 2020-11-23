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

        //Criando dados com Doctrine
        $product = new Product();
        $product->setName('Produto Teste');
        $product->setDescription('Descrição');
        $product->setBody('Info produto');
        $product->setSlug('produto-test');
        $product->setPrice(1990);
        $product->setCreatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));
        $product->setUpdateAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($product);
        $manager->flush();

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

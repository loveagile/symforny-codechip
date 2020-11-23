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
        /*$product = new Product();
        $product->setName('Produto Teste 2');
        $product->setDescription('Descrição 2');
        $product->setBody('Info produto 2');
        $product->setSlug('produto-test-2');
        $product->setPrice(3990);
        $product->setCreatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));
        $product->setUpdateAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($product);
        $manager->flush();*/

        //Atualizando dados com Doctrine
        /*$product = $this->getDoctrine()->getRepository(Product::class)->find(1);

        $product->setName('Produto Teste Editado');
        $product->setUpdateAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($product);
        $manager->flush();*/

        //Remoção de dados com Doctrine
        /*$product = $this->getDoctrine()->getRepository(Product::class)->find(1);

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($product);
        $manager->flush();*/

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

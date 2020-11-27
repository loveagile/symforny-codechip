<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/admin/products", name="admin_")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="index_product")
     */
    public function index(): Response
    {
        return $this->render('admin/product/index.html.twig', []);
    }

    /**
     * @Route("/create", name="create_product")
     */
    public function create()
    {
    }

    /**
     * @Route("/store", name="store_product", methods={"POST"})
     */
    public function store()
    {
        $product = new Product();
        $product->setName('Produto Teste 2');
        $product->setDescription('Descrição 2');
        $product->setBody('Info produto 2');
        $product->setSlug('produto-test-2');
        $product->setPrice(3990);
        $product->setCreatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));
        $product->setUpdateAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($product);
        $manager->flush();
    }

    /**
     * @Route("/edit/{product}", name="edit_products", methods={"POST"})
     */
    public function edit($product)
    {
    }

    /**
     * @Route("/update/{product}", name="update_products", methods={"POST"})
     */
    public function update($product)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find(1);

        $product->setName('Produto Teste Editado');
        $product->setUpdateAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($product);
        $manager->flush();
    }

    /**
     * @Route("/remove/{product", name="remove_products", methods={"POST"})
     */
    public function remove($product)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find(1);

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($product);
        $manager->flush();
    }
}
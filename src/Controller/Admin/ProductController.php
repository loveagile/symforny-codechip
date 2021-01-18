<?php

namespace App\Controller\Admin;

use App\Repository\ProductRepository;
use App\Service\UploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use App\Form;
/**
 * @Route("/admin/products", name="admin_")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="index_products")
     */
    public function index(ProductRepository $productRepository, UploadService $uploadService)
    {
        dump($uploadService->upload());

        $products = $productRepository->findAll();

        return $this->render('admin/product/index.html.twig', compact('products'));
    }

    /**
     * @Route("/create", name="create_products")
     */
    public function create(Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(Form\ProductType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $product->setCreatedAt();
            $product->setUpdateAt();

            $em->persist($product);
            $em->flush();

            $this->addFlash('sucess', 'Produto criado com sucesso!');

            return $this->redirectToRoute('admin_index_products');
        }

        return $this->render('admin/product/create.html.twig', [
            'form' => $form->createView()
        ]);

        //return $this->render('admin/product/create.html.twig', compact('products'));
    }

    /**
     * @Route("/edit/{product}", name="edit_products")
     */
    public function edit($product, Request $request, ProductRepository $productRepository, EntityManagerInterface $em)
    {
        $product = $productRepository->find($product);

        $form = $this->createForm(Form\ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $product = $form->getData();
            $product->setUpdateAt();

            $em->flush();

            $this->addFlash('sucess', 'Produto atualizado com sucesso!');

            return $this->redirectToRoute('admin_edit_products', ['product' => $product->getId()]);
        }

        return $this->render('admin/product/edit.html.twig', [
            'form' =>$form->createView()
            ]);
    }

    /**
     * @Route("/update/{product}", name="update_products", methods={"POST"})
     */
    public function update($product, Request $request)
    {
        try {
            $data = $request->request->all();
            $product = $this->getDoctrine()->getRepository(Product::class)->find($product);

            $product->setName($data['name']);
            $product->setDescription($data['description']);
            $product->setBody($data['body']);
            $product->setSlug($data['slug']);
            $product->setPrice($data['price']);

            $product->setUpdateAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($product);
            $manager->flush();

            $this->addFlash('warning', 'Produto atualizado com sucesso!');

            return $this->redirectToRoute('admin_edit_products', ['product' => $product->getId()]);

        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * @Route("/remove/{product}", name="remove_products")
     */
    public function remove($product, ProductRepository $productRepository)
    {
        try {
            $product = $productRepository->find($product);

            $manager = $this->getDoctrine()->getManager();
            $manager->remove($product);
            $manager->flush();

            $this->addFlash('danger', 'Produto removido com sucesso!');

            return $this->redirectToRoute('admin_index_products');

        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\ProductPhoto;
use App\Repository\ProductRepository;
use App\Service\UploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\{File\UploadedFile, Request, Response};
use App\Form;
use Gedmo\Timestampable\Traits\TimestampableEntity;
/**
 * @Route("/admin/products", name="admin_")
 */
class ProductController extends AbstractController
{
    use TimestampableEntity;
    /**
     * @Route("/", name="index_products")
     */
    public function index(ProductRepository $productRepository)
    {
        $products = $productRepository->findAll();

        return $this->render('admin/product/index.html.twig', compact('products'));
    }

//    /**
//     * @Route("/upload")
//     */
//    public function upload(Request $request, UploadService $uploadService)
//    {
//        /**@var UploadedFile[] $photos */
//        $photos = $request->files->get('photos');
//        $uploadService->upload($photos, 'products');
//
//        return new Response('Upload');
//    }

    /**
     * @Route("/create", name="create_products")
     */
    public function create(Request $request, EntityManagerInterface $em, UploadService $uploadService)
    {
        $form = $this->createForm(Form\ProductType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $product = $form->getData();

            $photosUpload = $form['photos']->getData();

            if ($photosUpload) {
                $photosUpload = $uploadService->upload($photosUpload, 'products');
                $photosUpload = $this->makeProductPhotoEntities($photosUpload);

                $product->addManyProductPhoto($photosUpload);
            }

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
    public function edit($product, Request $request, ProductRepository $productRepository, EntityManagerInterface $em, UploadService $uploadService)
    {
        $product = $productRepository->find($product);

        $form = $this->createForm(Form\ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $product = $form->getData();

            $photosUpload = $form['photos']->getData();

            if ($photosUpload) {
                $photosUpload = $uploadService->upload($photosUpload, 'products');
                $photosUpload = $this->makeProductPhotoEntities($photosUpload);
                dump($photosUpload);
                $product->addManyProductPhoto($photosUpload);
            }

            $em->flush();

            $this->addFlash('sucess', 'Produto atualizado com sucesso!');

            return $this->redirectToRoute('admin_edit_products', ['product' => $product->getId()]);
        }

        return $this->render('admin/product/edit.html.twig', [
            'form' => $form->createView(),
            'productPhotos' => $product->getProductPhotos()
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

    private function makeProductPhotoEntities($uploadedPhotos)
    {
        $entities = [];

        foreach($uploadedPhotos as $photo) {
            $productPhoto = new ProductPhoto();
            $productPhoto->setPhoto($photo);
            $productPhoto->setCreatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));
            $productPhoto->setUpdatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));
            $entities[] = $productPhoto;
        }

        return $entities;
    }
}

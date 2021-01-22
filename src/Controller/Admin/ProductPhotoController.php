<?php

namespace App\Controller\Admin;

use App\Entity\ProductPhoto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductPhotoController extends AbstractController
{
    /**
     * @Route("/product/photo/{productPhoto}", name="admin_product_photo_remove")
     */
    public function remove(ProductPhoto $productPhoto, EntityManagerInterface $em)
    {
        $product = $productPhoto->getProduct()->getId();
        $realImage = $this->getParameter('upload_dir') . '/products/' . $productPhoto->getPhoto();

        $em->remove($productPhoto);
        $em->flush();

        if(file_exists($realImage))
            unlink($realImage);

        return $this->redirectToRoute('admin_products_edit', ['product' => $product]);

    }
}

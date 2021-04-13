<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private $cart;

    public function __construct(CartService $cart)
    {
        $this->cart = $cart;
    }

    /**
     * @Route("/cart", name="home_cart", priority="10")
     */
    public function index(): Response
    {
        $cart =  $this->cart->getAll();
        
        return $this->render('cart.html.twig', compact('cart'));
    }

    /**
     * @Route("/cart/add/{item}", name="home_cart_add")
     */
    public function add($item, ProductRepository $productRepository, Request $request)
    {
        if($request->request->get('amount') <= 0){
            return $this->redirectToRoute('home');
        }

        $product = $productRepository->findProductToCartBySlug($item);

        if(!$product) return $this->redirectToRoute('home');

        $product['price'] = $product['price'] / 100;
        $product['amount'] = $request->request->get('amount');

        $result = $this->cart->addItem($product);

        $this->addFlash('success', $result);

        return $this->redirectToRoute('product_single', ['slug' => $product['slug']]);
    }

    /**
     * @Route("/cart/remove/{item}", name="home_cart_remove")
     */
    public function remove($item)
    {
        $this->cart->removeItem($item);

        $this->addFlash('success', 'Produto removido com sucesso!');

        return $this->redirectToRoute('home_cart');
    }

    /**
     * @Route("/cart/destroy", name="home_cart_destroy")
     */
    public function destroy()
    {
        $this->cart->destroyCart();

        $this->addFlash('success', 'Carrinho cancelado com sucesso!');

        return $this->redirectToRoute('home_cart');
    }

    /**
     * @Route("/cart/pay", name="home_cart_pay")
     */
    public function pay(){
        return true;
    }
}

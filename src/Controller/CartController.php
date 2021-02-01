<?php

namespace App\Controller;

use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        dump($cart);
        return $this->render('cart.html.twig', compact('cart'));
    }

    /**
     * @Route("/cart/add/{item}", name="home_cart_add")
     */
    public function add($item)
    {
        $item = [
            'name' => 'Produto Teste' . rand(1, 100),
            'slug' => $item,
            'price' => 1999,
            'amount' => 3
        ];

        $this->cart->addItem($item);

        return $this->redirectToRoute('product_single', ['slug' => $item['slug']]);
    }

    /**
     * @Route("/cart/remove/{item}", name="home_cart_remove")
     */
    public function remove($item)
    {
        $this->cart->removeItem($item);

        return $this->redirectToRoute('home_cart');
    }
}

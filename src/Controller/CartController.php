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
        dd($this->cart->getAll());
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/CartController.php',
        ]);
    }

    /**
     * @Route("/cart/add/{item}", name="home_cart_add")
     */
    public function add($item)
    {
        $item = [
            'name' => 'Produto Teste' . rand(1, 100),
            'slug' => $item . rand(1, 100),
            'price' => 1999
        ];

        $this->cart->addItem($item);

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/CartController.php',
        ]);
    }
}

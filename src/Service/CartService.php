<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(SessionInterface $session)
    {

        $this->session = $session;
    }

    public function getAll()
    {
        return $this->session->has('cart') ? $this->session->get('cart') : [];
    }

    public function addItem($item)
    {
        $cart = $this->getAll();

        if(count($cart)) {
            array_push($cart, $item);
        } else {
            $cart[] = $item;
        }

        $this->session->set('cart', $cart);

        return true;
    }

    public function removeItem($item)
    {
        $cart = $this->getAll();

        if(count($cart) == 0) $cart;

        $cart = array_filter($cart, function($itemArr) use($item){
            return $itemArr['slug'] != $item;
        });

        if(count($cart) == 0)
            return $this->session->remove('cart');

        $this->session->set('cart', $cart);
    }
}
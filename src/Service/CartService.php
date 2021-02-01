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
        //Se tem carrinho na sessão
        $cart = $this->getAll();

        //Se tiver eu adiciono na sessão atual
        if(count($cart)) {
            array_push($cart, $item);
        } else {
            //Se não tiver eu crio a sessao cart...
            $cart[] = $item;
        }

        $this->session->set('cart', $cart);

        return true;
    }

    public function removeItem($item)
    {

    }
}
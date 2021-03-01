<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CheckoutController extends AbstractController
{
    /**
     * @Route("/checkout", name="home_checkout", priority="10")
     * @IsGranted("ROLE_USER")
     */
    public function index(Request $request)
    {
        $session = $request->getSession();
        if(!$session->has('cart')) {
            return $this->redirectToRoute('home');
        }

        $cart = $session->get('cart');

        return $this->render('checkout.html.twig', compact('cart'));
    }
    /**
     * @Route("/checkout/thanks", name="home_checkout_finished", priority="10")
     * @IsGranted("ROLE_USER")
     */
    public function thanks(Request $request)
    {
        if($request->getSession()->has('cart')) {
            $request->getSession()->remove('cart');
        }
        return new Response('Obrigado por sua compra!');
    }
}

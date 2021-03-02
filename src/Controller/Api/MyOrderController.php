<?php

namespace App\Controller\Api;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/orders", name="api_orders_")
 */
class MyOrderController extends AbstractController
{
    /**
     * @Route("/", name="get")
     */
    public function index(UserRepository $repo)
    {
        $user = $repo->find(1);
        return $this->json([
            'data' => [
                'orders' => $user->getOrder()
            ]
        ]);
    }
}

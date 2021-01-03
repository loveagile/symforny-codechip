<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Address;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index(): Response
    {
        $name = 'Lwcyano Will';

        $manager = $this->getDoctrine()->getManager();

        $user = new User();
        $user->setFirstName('Harry');
        $user->setLastName('Will');
        $user->setEmail('willvix@outlook.com');
        $user->setPassword('10');
        $user->setCreatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));
        $user->setUpdateAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));

        $manager->persist($user);
        $manager->flush();

        $address = new Address();
        $address->setAddress('Rua Holdecim');
        $address->setNumber(840);
        $address->setNeighborhood('Civit 2');
        $address->setCity('Serra');
        $address->setState('ES');
        $address->setZipcode('29168-066');
        $address->setUser($user);

        $manager->persist($address);
        $manager->flush();

        return $this->render('index.html.twig', compact('name'));
    }

    /**
     * @Route("/product/{slug}", name="product_single")
     */
    public function product($slug): Response
    {
        return $this->render('single.html.twig', compact('slug'));
    }
}

<?php

namespace App\Controller\Api;

use App\Form\UserProfileType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/api/profiles", name="api_profiles_")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/{user}", name="update", methods={"PUT"})
     */
    public function profile(Request $request, UserRepository $repo)
    {
        $user = $repo->find(1);
        $form = $this->createForm(UserProfileType::class, $user);
        $form->submit($request->request->all());

        $this->getDoctrine()->getManager()->flush();

        return $this->json([
            'data' => [
                'message' => 'Perfil atualizado com sucesso!'
                ]
        ]);
    }

    /**
     * @Route("/password", name="update_password", methods={"PUT", "PATCH"})
     */
    public function index(Request $request, UserRepository $repo, UserPasswordEncoderInterface $passwordEncoder)
    {
        $plainPassword = $request->request->get('password');

        if(!$plainPassword) {
            return $this->json([
                'data' => [
                    'message' => 'O campo senha é requerido...'
                ]
            ], 400);
        }
        $user = $repo->find(1);

        $password = $passwordEncoder->encodePassword($user, $plainPassword);
        $user->setPassword($password);

        $this->getDoctrine()->getManager()->flush();

        return $this->json([
            'message' => 'Senha atualizada com sucesso!',
        ]);
    }
}

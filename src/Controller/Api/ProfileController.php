<?php

namespace App\Controller\Api;

use App\Form\UserProfileType;
use App\Repository\UserRepository;
use App\Service\Api\FormErrorsValidation;
use phpDocumentor\Reflection\Types\This;
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
     * @Route("/{user}", name="get", methods={"GET"})
     */
    public function userProfile(UserRepository $repo, $user)
    {
        $user = $repo->find($user);

        return $this->json([
            'data' => [
                'profile' => $user
                ]
        ],200,[],['groups' => ['profile']]);
    }

    /**
     * @Route("/{user}", name="update", methods={"PUT"})
     */
    public function profile(Request $request, UserRepository $repo, $user, FormErrorsValidation $formErrors)
    {
        $user = $repo->find($user);
        $form = $this->createForm(UserProfileType::class, $user);
        $form->submit($request->request->all());

        if(!$form->isValid()) {
            return $this->json(['data' => [
                'errors' => $formErrors->getErrors($form)
            ]], 400);
        }
        dd($user);
        $this->getDoctrine()->getManager()->flush();
        dd('ok');
        return $this->json([
            'data' => [
                'message' => 'Perfil atualizado com sucesso!'
            ]
        ]);
    }

    /**
     * @Route("/password", name="update_password", methods={"PUT", "PATCH"}, priority="10")
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

    /**
     * @Route("/recovery/", name="recovery_password", methods={"PUT","GET", "POST"})
     */
    public function recovery(Request $request, UserRepository $repo) {
        $id = $request->request->get('id');
        //echo json_encode("Is value ID: ".$id);
        $data = $repo->find($id);
        $pass = $data->getPassword();
        return $this->json($pass);
    }
}

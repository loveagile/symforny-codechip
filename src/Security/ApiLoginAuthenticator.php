<?php

namespace App\Security;

use App\Repository\UserRepository;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class ApiLoginAuthenticator extends AbstractGuardAuthenticator
{
    private $userRepository;
    private $passwordEncoder;

    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function supports(Request $request)
    {
        return $request->attributes->get('_route') == 'app_login_api' &&
            $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        return [
            'username' => $request->request->get('username'),
            'password' => $request->request->get('password')
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $this->userRepository->findOneByEmail($credentials['username']);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse(['data' =>  [
            'error' => $exception->getMessageKey()
        ]]);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        return new JsonResponse(['JWT']);
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        // todo
    }

    public function supportsRememberMe()
    {
        return false;
    }
}

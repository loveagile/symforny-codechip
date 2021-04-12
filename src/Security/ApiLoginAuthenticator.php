<?php

namespace App\Security;

use App\Repository\UserRepository;
use App\Service\Api\Token\Jwt;
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
    private $jwt;

    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder, Jwt $jwt)
    {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->jwt = $jwt;
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

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $claims = [
            'uid' => $token->getUser()->getId(),
            'email' => $token->getUser()->getEmail()
        ];

        $token = $this->jwt->generate($claims);

        return new JsonResponse(['data' => [
            'token' => (string) $token
        ]]);
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

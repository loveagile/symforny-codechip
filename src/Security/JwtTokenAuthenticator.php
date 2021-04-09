<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\HttpFoundation\Response;
use Lcobucci\JWT\Parser;

class JwtTokenAuthenticator extends AbstractGuardAuthenticator
{
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function supports(Request $request)
    {
        return $request->headers->has('Authorization')
            && strpos($request->headers->get('Authorization'), 'Bearer ') === 0;
    }

    public function getCredentials(Request $request)
    {
        $token = $request->headers->get('Authorization');

        return substr($token, 7);
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = (new Parser())->parse((string) $credentials);

        //validar os dados do token...

        return $this->userRepository->findOneByEmail($token->getClaim('email'));
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse(['data' =>  [
            'error' => $exception->getMessageKey()
        ]]);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        // todo
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        // todo
    }

    public function supportsRememberMe()
    {
        // todo
    }
}

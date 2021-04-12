<?php

namespace App\Security;

use App\Repository\UserRepository;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\HttpFoundation\Response;
use Lcobucci\JWT\Parser;

class JwtTokenAuthenticator extends AbstractGuardAuthenticator
{
    private $userRepository;
    private $key;

    public function __construct(UserRepository $userRepository, string $key)
    {
        $this->userRepository = $userRepository;
        $this->key = $key;
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

        if (!$token->verify(new Sha256(), $this->key)) {
            throw new CustomUserMessageAuthenticationException('Invalid Token');
        }

        if ($token->isExpired()) {
            throw new CustomUserMessageAuthenticationException('Token Expired');
        }

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
        return new JsonResponse([
            'data' => [
                'message' => 'Token not provided!'
            ]
        ], 401);
    }

    public function supportsRememberMe()
    {
        // todo
    }
}

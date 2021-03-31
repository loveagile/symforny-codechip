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
use Lcobucci\JWT;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Hmac\Sha256;

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

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $signer = new Sha256();
        $time = time();
        $user = $token->getUser();

        $token = (new Builder())->issuedBy('http://127.0.0.1:8000') // Configures the issuer (iss claim)
        ->permittedFor('http://127.0.0.1:8000') // Configures the audience (aud claim)
        ->identifiedBy($user->getId(), true) // Configures the id (jti claim), replicating as a header item
        ->issuedAt($time) // Configures the time that the token was issue (iat claim)
        ->canOnlyBeUsedAfter($time + 60) // Configures the time that the token can be used (nbf claim)
        ->expiresAt($time + 3600) // Configures the expiration time of the token (exp claim)
        ->withClaim('uid', $user->getId()) // Configures a new claim, called "uid"
        ->withClaim('email', $user->getEmail())
        ->getToken($signer, new Key('testing')); // Retrieves the generated token

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

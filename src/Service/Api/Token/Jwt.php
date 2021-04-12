<?php


namespace App\Service\Api\Token;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Hmac\Sha256;


class Jwt
{
    private $key;
    private $host;

    public function __construct(string $key, string $host)
    {
        $this->key = $key;
        $this->host = $host;
    }

    public function generate(array $claimsData)
    {
        $signer = new Sha256();
        $time = time();

        $token = (new Builder())->issuedBy($this->host) // Configures the issuer (iss claim)
                                ->permittedFor($this->host) // Configures the audience (aud claim)
                                ->identifiedBy($claimsData['uid'], true) // Configures the id (jti claim), replicating as a header item
                                ->issuedAt($time) // Configures the time that the token was issue (iat claim)
                                ->canOnlyBeUsedAfter($time + 60) // Configures the time that the token can be used (nbf claim)
                                ->expiresAt($time + 3600); // Configures the expiration time of the token (exp claim)

        foreach ($claimsData as $key => $data) {
            $token->withClaim($key, $data);
        }

        return $token->getToken($signer, new Key($this->key)); // Retrieves the generated token
    }

}
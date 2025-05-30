<?php

namespace App\com;

use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;

class JWTUser implements JWTUserInterface
{

    public function __construct(public $username,public array $roles)
    {

    }
    /**
     * @inheritDoc
     */
    public static function createFromPayload($username, array $payload)
    {
        return new self(
            $username,
            $payload['roles'], // Added by default
        );
    }

    /**
     * @inheritDoc
     */
    public function getRoles(): array
    {
        return  $this->roles;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @inheritDoc
     */
    public function getUserIdentifier(): string
    {
        return $this->username;
    }
}
<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use App\Api\Processor\CreateUserProcessor;
use App\Api\Resource\CreateUser;
use App\Doctrine\Traits\UuidTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Ignore;

#[ORM\Entity]
#[ORM\UniqueConstraint(name: 'UNIQ_USER_IDENTIFIER_EMAIL', fields: ['email'])]
#[Get]
#[GetCollection]
#[Post]
#[Put]
#[Delete]
#[Post(input: CreateUser::class, processor: CreateUserProcessor::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use UuidTrait;

    #[ORM\Column(length: 180)]
    public ?string $email = null;

    #[ORM\Column]
    public array $roles = [];

    #[ORM\Column]
    #[Ignore]
    public ?string $password = null;

    public function __construct()
    {
        $this->defineUuid();
    }

    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function eraseCredentials(): void
    {
    }
}

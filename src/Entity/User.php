<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use App\Doctrine\Trait\TimestampableTrait;
use App\Doctrine\Trait\UuidTrait;
use App\Enum\TableEnum;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ApiResource]
#[ORM\Table(name: TableEnum::USER)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableTrait, UuidTrait;

    #[ORM\Column]
    #[Assert\NotBlank]
    public ?string $firstName = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    public ?string $lastName = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Email]
    public ?string $email = null;

    #[ORM\Column]
    public ?string $password = null;

    #[ORM\Column]
    public array $roles = [];

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

    public function __construct()
    {
        $this->defineUuid();
    }
}

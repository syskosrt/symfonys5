<?php declare(strict_types=1);

namespace App\Entity;

use App\Api\Resource\CreateUserResource;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use App\Api\Processor\CreateUserProcessor;
//use App\Api\Processor\EditUserProcessor;
//use App\Api\Resource\EditUser;
use App\Api\Processor\DeleteUserProcessor;
use App\Doctrine\Trait\TimestampableTrait;
use App\Doctrine\Trait\UuidTrait;
use App\Enum\TableEnum;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Ignore;
use App\Validator\UnregistredEmail;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity]
#[ApiResource]
#[ORM\Table(name: TableEnum::USER)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[Post(input: CreateUserResource::class, processor: CreateUserProcessor::class)]
//#[Put(input: EditUser::class, processor: EditUserProcessor::class)]
#[Delete(processor: DeleteUserProcessor::class)]
#[GetCollection]
#[Get]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableTrait, UuidTrait;

    #[ORM\Column(nullable: true)]
    public ?string $firstName = null;

    #[ORM\Column(nullable: true)]
    public ?string $lastName = null;


    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Email]
    #[UnregistredEmail]
    public ?string $email = null;

    #[ORM\Column]
    #[Ignore] # Cet attribut est ignoré par le générateur de schéma de la base de données
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

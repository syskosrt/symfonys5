<?php declare(strict_types=1);

namespace App\Api\Resource;

use App\Validator\UnregistredEmail;
use Symfony\Component\Validator\Constraints as Assert;

class CreateUserResource
{

    #[Assert\NotBlank]
    public ?string $firstName = null;

    #[Assert\NotBlank]
    public ?string $lastName = null;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[UnregistredEmail]
    public ?string $email = null;

    #[Assert\NotBlank]
    public ?string $password = null;

    /** @var array<string> */

    public ?array $roles = [];
}

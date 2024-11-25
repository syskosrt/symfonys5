<?php

namespace App\Api\Resource;
use App\Validator\UnregistredEmail;
use Symfony\Component\Validator\Constraints as Assert;

class CreateUserResource
{

    #[Assert\NotBlank]
    #[Assert\Email]
    #[UnregistredEmail]
    public ?string $email = null;

    #[Assert\NotBlank]
    public ?string $password = null;
}

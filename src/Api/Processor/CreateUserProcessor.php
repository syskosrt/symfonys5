<?php declare(strict_types=1);

namespace App\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Api\Resource\CreateUser;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class CreateUserProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $hasher,
    ) {}

    /** @param CreateUser $data */
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = [],
    ): User {
        if (!$data instanceof CreateUser) {
            throw new \InvalidArgumentException('Expected instance of CreateUser');
        }

        $user = new User();

        // Assign the email
        $user->email = $data->email;

        // Check that the password is not empty, then hash it
        if (!empty($data->password)) {
            $user->password = $this->hasher->hashPassword($user, $data->password);
        } else {
            throw new \InvalidArgumentException('Password cannot be empty');
        }

        // Set default roles if not provided
        $user->roles = $data->roles ?? ['ROLE_USER'];

        // Persist the user
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}

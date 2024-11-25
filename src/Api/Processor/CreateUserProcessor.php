<?php declare(strict_types=1);

namespace App\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Api\Resource\CreateUserResource;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class CreateUserProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $hasher,
    ) {
    }

    /** @param CreateUserResource $data */
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = [],
    ): User {
        // Créez un nouvel utilisateur à partir des données
        $user = new User();
        $user->email = $data->email;
        $user->password = $this->hasher->hashPassword($user, $data->password);

        // Vous pouvez également ajouter d'autres colonnes ici si nécessaire
        $user->firstName = $data->firstName ?? null;
        $user->lastName = $data->lastName ?? null;

        // Persist et flush pour sauvegarder dans la base de données
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}

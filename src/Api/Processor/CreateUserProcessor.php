<?php declare(strict_types=1);

namespace App\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Api\Resource\CreateUserResource;
use App\Entity\User;
use App\Enum\RoleEnum;
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

        // Ajout des rôles
        $roles = $data->roles ?? []; // Récupère les rôles supplémentaires s'ils sont fournis
        $roles[] = RoleEnum::ROLE_USER; // Assigne toujours le rôle ROLE_USER
        $user->roles = array_unique($roles); // Évite les doublons dans les rôles

        // Ajout des autres colonnes
        $user->firstName = $data->firstName ?? null;
        $user->lastName = $data->lastName ?? null;

        // Persist et flush pour sauvegarder dans la base de données
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}

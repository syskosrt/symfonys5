<?php declare(strict_types=1);

namespace App\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;

final readonly class DeleteUserProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = [],
    ): void {
        // Rechercher l'utilisateur par son UUID
        $user = $this->em->getRepository(User::class)->findOneBy(['uuid' => $uriVariables['uuid'] ?? null]);
        if (!$user) {
            throw new InvalidArgumentException('User not found');
        }

        // Supprimer l'utilisateur
        $this->em->remove($user);
        $this->em->flush();
    }
}

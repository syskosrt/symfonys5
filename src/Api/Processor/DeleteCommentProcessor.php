<?php declare(strict_types=1);

namespace App\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;

final readonly class DeleteCommentProcessor implements ProcessorInterface
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
        // Rechercher le commentaire par son identifiant
        $comment = $this->em->getRepository(Comment::class)->find($uriVariables['id']);
        if (!$comment) {
            throw new InvalidArgumentException('Comment not found');
        }

        // Supprimer le commentaire
        $this->em->remove($comment);
        $this->em->flush();
    }
}

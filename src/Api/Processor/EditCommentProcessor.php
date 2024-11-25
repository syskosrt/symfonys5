<?php declare(strict_types=1);

namespace App\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Api\Resource\EditCommentResource;
use App\Entity\Comment;
use App\Entity\Content;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

final readonly class EditCommentProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    /** @param EditCommentResource\ $data */
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = [],
    ): Comment {
        // Rechercher le commentaire par son identifiant
        $comment = $this->em->getRepository(Comment::class)->find($uriVariables['id']);
        if (!$comment) {
            throw new InvalidArgumentException('Comment not found');
        }

        // Rechercher l'auteur par son identifiant (par exemple, email ou UUID)
        if ($data->author) {
            $author = $this->em->getRepository(User::class)->findOneBy(['email' => $data->author]);
            if (!$author) {
                throw new UserNotFoundException('Author not found');
            }
            $comment->author = $author;
        }

        // Rechercher le contenu par son identifiant
        if ($data->content) {
            $content = $this->em->getRepository(Content::class)->find($data->content);
            if (!$content) {
                throw new InvalidArgumentException('Content not found');
            }
            $comment->content = $content;
        }

        // Mettre Ã  jour le commentaire
        $comment->comment = $data->comment ?? $comment->comment;

        $this->em->flush();

        return $comment;
    }
}

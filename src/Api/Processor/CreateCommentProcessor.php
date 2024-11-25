<?php declare(strict_types=1);

namespace App\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Api\Resource\CreateCommentResource;
use App\Entity\Comment;
use App\Entity\Content;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

final readonly class CreateCommentProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    /** @param CreateCommentResource $data */
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = [],
    ): Comment {




        // Créer le commentaire
        $comment = new Comment();
        $comment->comment = $data->comment;


        // Rechercher l'auteur par son identifiant (par exemple, email ou UUID)
        // Gère l'auteur (relation avec User)
        if ($data->author instanceof User) {
            $comment->setAuthor($data->author);
        } elseif (is_string($data->author)) {
            // Si l'auteur est un IRI, le récupérer depuis la base
            $author = $this->em->getRepository(User::class)->find($data->author);
            if (!$author) {
                throw new InvalidArgumentException('Author not found.');
            }
            $comment->setAuthor($author);
        } else {
            throw new InvalidArgumentException('Invalid author data.');
        }


        // Rechercher l'entité Content
        if ($data->content instanceof Content) {
            $comment->content = $data->content;
        } elseif (is_string($data->content)) {
            $content = $this->em->getRepository(Content::class)->find($data->content);
            if (!$content) {
                throw new InvalidArgumentException('Content not found.');
            }
            $comment->content = $content;
        } else {
            throw new InvalidArgumentException('Invalid content data.');
        }


        $this->em->persist($comment);
        $this->em->flush();

        return $comment;
    }
}

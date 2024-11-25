<?php declare(strict_types=1);

namespace App\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Api\Resource\EditContentResource;
use App\Entity\Content;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

final readonly class EditContentProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    /** @param EditContentResource $data */
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = [],
    ): Content {
        // Rechercher le contenu par son identifiant
        $content = $this->em->getRepository(Content::class)->find($uriVariables['id']);
        if (!$content) {
            throw new InvalidArgumentException('Content not found');
        }

        // Rechercher l'auteur par son identifiant (par exemple, email ou UUID)
        if ($data->author) {
            $author = $this->em->getRepository(User::class)->findOneBy(['email' => $data->author]);
            if (!$author) {
                throw new UserNotFoundException('Author not found');
            }
            $content->author = $author;
        }

        // Mettre Ã  jour le contenu
        $content->title = $data->title ?? $content->title;
        $content->content = $data->content ?? $content->content;
        $content->cover = $data->cover ?? $content->cover;
        $content->tags = $data->tags ?? $content->tags;

        $this->em->flush();

        return $content;
    }
}

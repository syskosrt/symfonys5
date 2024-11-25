<?php declare(strict_types=1);

namespace App\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Content;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;

final class CreateContentProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    /** @param Content $data */
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = [],
    ): Content {
        // Crée un nouvel objet Content
        $content = new Content();

        // Affecte les valeurs simples
        $content->setTitle($data->title ?? null);
        $content->setContent($data->content ?? null);
        $content->setCover($data->cover ?? null);
        $content->setMetaTitle($data->metaTitle ?? null);
        $content->setMetaDescription($data->metaDescription ?? null);
        $content->setPublished($data->published ?? false);
        $content->setViews($data->views ?? 0);

        // Gère l'auteur (relation avec User)
        if ($data->author instanceof User) {
            $content->setAuthor($data->author);
        } elseif (is_string($data->author)) {
            // Si l'auteur est un IRI, le récupérer depuis la base
            $author = $this->em->getRepository(User::class)->find($data->author);
            if (!$author) {
                throw new InvalidArgumentException('Author not found.');
            }
            $content->setAuthor($author);
        } else {
            throw new InvalidArgumentException('Invalid author data.');
        }

        // Gère les tags (relation ManyToMany avec Tag)
        if (!empty($data->tags)) {
            foreach ($data->tags as $tagName) {
                // Recherche un tag existant ou crée un nouveau
                $tag = $this->em->getRepository(Tag::class)->findOneBy(['name' => $tagName]);
                if (!$tag) {
                    $tag = new Tag();
                    $tag->setName($tagName);
                    $this->em->persist($tag); // Persist le nouveau tag
                }
                $content->addTag($tag);
            }
        }

        // Persist et flush
        $this->em->persist($content);
        $this->em->flush();

        return $content;
    }
}

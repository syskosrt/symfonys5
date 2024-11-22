<?php declare(strict_types=1);

namespace App\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Api\Resource\CreateContentResource;
use App\Entity\Content;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\String\Slugger\SluggerInterface;

final readonly class CreateContentProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,      // Entity Manager pour interagir avec la base de données
        private SluggerInterface $slugger       // Pour générer un slug unique
    ) {
    }

    /** @param CreateContentResource $data */
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): Content {
        // Récupérer l'auteur du contenu
        $author = $this->em->getRepository(User::class)->find($data->authorId);
        if (!$author) {
            throw new AccessDeniedException('Author not found.');
        }

        // Créer une nouvelle entité Content
        $content = new Content();
        $content->setTitle($data->title);
        $content->setCoverImage($data->coverImage);
        $content->setMetaTitle($data->metaTitle);
        $content->setMetaDescription($data->metaDescription);
        $content->setContent($data->content);
        $content->setTags($data->tags ?? []);
        $content->setAuthor($author);

        // Générer un slug à partir du titre
        $slug = $this->slugger->slug($data->title)->lower()->toString();
        $content->setSlug($slug);

        // Sauvegarder le contenu en base de données
        $this->em->persist($content);
        $this->em->flush();

        return $content;
    }
}

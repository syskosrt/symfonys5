<?php declare(strict_types=1);

namespace App\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Content;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;

final readonly class DeleteContentProcessor implements ProcessorInterface
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
        // Rechercher le contenu par son UUID
        $content = $this->em->getRepository(Content::class)->findOneBy(['uuid' => $uriVariables['uuid'] ?? null]);
        if (!$content) {
            throw new InvalidArgumentException('Content not found');
        }

        // Supprimer le contenu
        $this->em->remove($content);
        $this->em->flush();
    }
}

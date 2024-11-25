<?php declare(strict_types=1);

namespace App\Api\Resource;

use App\Entity\User;

class CreateContentResource
{
    public ?string $title = null;
    public ?string $content = null;
    public ?string $cover = null;

    /**
     * Liez l'IRI de l'utilisateur à l'entité User
     */
    public ?User $author = null;

    public ?array $tags = [];
}

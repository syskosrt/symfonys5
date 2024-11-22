<?php declare(strict_types=1);

namespace App\Api\Resource;

final class CreateContentResource
{
    public int $authorId;
    public string $title;
    public ?string $coverImage = null;
    public ?string $metaTitle = null;
    public ?string $metaDescription = null;
    public string $content;
    public ?array $tags = null;
}

<?php declare(strict_types=1);

namespace App\Api\Resource;

class EditContentResource
{
    public ?string $title = null;
    public ?string $content = null;
    public ?string $cover = null;
    public ?string $author = null; // Assuming author is passed as a string (e.g., UUID or email)
    public ?array $tags = [];
}

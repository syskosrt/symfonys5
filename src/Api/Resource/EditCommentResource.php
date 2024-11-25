<?php declare(strict_types=1);

namespace App\Api\Resource;

class EditCommentResource
{
    public ?string $comment = null;
    public ?string $author = null; // Assuming author is passed as a string (e.g., UUID or email)
    public ?string $content = null; // Assuming content is passed as a string (e.g., UUID)
}

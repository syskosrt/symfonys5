<?php declare(strict_types=1);

namespace App\Api\Resource;

use App\Entity\User;
use App\Entity\Content;

class CreateCommentResource
{
    public ?string $comment = null;
    public ?User $author = null;
    public ?Content $content = null;
}

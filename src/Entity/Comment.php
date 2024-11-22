<?php declare(strict_types=1);

namespace App\Entity;

use App\Enum\TableEnum;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use App\Doctrine\Trait\UuidTrait;

#[ORM\Table(name: TableEnum::COMMENT)]
class Comment
{
    use UuidTrait;

    #[ORM\Column(type: Types::STRING)]
    public ?string $comment = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'author_uuid', referencedColumnName: 'uuid' ,nullable: false)]
    public ?User $author = null;

    #[ORM\ManyToOne(targetEntity: Content::class)]
    #[ORM\JoinColumn(name: 'content_uuid', referencedColumnName: 'uuid' ,nullable: false)]
    public ?Content $content = null;

    public function __construct()
    {

    }
}

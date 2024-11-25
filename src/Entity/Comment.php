<?php declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\NumericFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Api\Processor\CreateCommentProcessor;
use App\Api\Processor\DeleteCommentProcessor;
use App\Api\Processor\EditCommentProcessor;
use App\Api\Resource\CreateCommentResource;
use App\Api\Resource\EditCommentResource;
use App\Doctrine\Trait\TimestampableTrait;
use App\Doctrine\Trait\UuidTrait;
use App\Enum\TableEnum;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ApiResource()]
#[ORM\Table(name: TableEnum::COMMENT)]
#[Post(input: CreateCommentResource::class, processor: CreateCommentProcessor::class)]
#[Put(input: EditCommentResource::class, processor: EditCommentProcessor::class, security: 'is_granted("ROLE_USER") and object.author == user')]
#[Get]
#[GetCollection]
#[Delete(processor: DeleteCommentProcessor::class)]
// #[ApiFilter(SearchFilter::class, properties: ['comment' => 'partial', 'author.email' => 'exact', 'content.title' => 'partial'])]
// #[ApiFilter(DateFilter::class, properties: ['createdAt'])]
// #[ApiFilter(BooleanFilter::class, properties: ['published'])]
// #[ApiFilter(NumericFilter::class, properties: ['views'])]
// #[ApiFilter(OrderFilter::class, properties: ['createdAt', 'comment'])]
class Comment
{
    use UuidTrait;
    use TimestampableTrait;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    public ?string $comment = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'author_uuid', referencedColumnName: 'uuid', nullable: false, onDelete: 'CASCADE')]
    public ?User $author = null;

    #[ORM\ManyToOne(targetEntity: Content::class)]
    #[ORM\JoinColumn(name: 'content_uuid', referencedColumnName: 'uuid', nullable: false, onDelete: 'CASCADE')]
    public ?Content $content = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    public bool $published = false;

    #[ORM\Column(type: Types::INTEGER)]
    public int $views = 0;


    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }


    public function __construct()
    {
        $this->defineUuid();
    }
}

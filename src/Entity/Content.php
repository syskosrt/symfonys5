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
use App\Api\Processor\CreateContentProcessor;
use App\Api\Processor\DeleteContentProcessor;
use App\Api\Processor\EditContentProcessor;
use App\Api\Resource\CreateContentResource;
use App\Api\Resource\EditContentResource;
use App\Doctrine\Trait\TimestampableTrait;
use App\Doctrine\Trait\UuidTrait;
use App\Enum\RoleEnum;
use App\Enum\TableEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: TableEnum::CONTENT)]
#[ApiResource()]
#[Get]
#[GetCollection]
#[Post(input: CreateContentResource::class, processor: CreateContentProcessor::class)]
#[Put(input: EditContentResource::class, processor: EditContentProcessor::class, security: 'is_granted("' . RoleEnum::ROLE_ADMIN . '") and object.author == user')]
#[Delete(processor: DeleteContentProcessor::class, security: 'is_granted("' . RoleEnum::ROLE_ADMIN . '")')]
#[ApiFilter(SearchFilter::class, properties: ['title' => 'partial'])]
#[ApiFilter(DateFilter::class, properties: ['createdAt'])]
#[ApiFilter(BooleanFilter::class, properties: ['published'])]
#[ApiFilter(NumericFilter::class, properties: ['views'])]
#[ApiFilter(OrderFilter::class, properties: ['createdAt', 'title'])]
class Content
{
    use UuidTrait;
    use TimestampableTrait;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    public ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    public ?string $content = null;

    #[ORM\Column(type: Types::STRING)]
    public ?string $cover = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $metaTitle = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $metaDescription = null;

    #[ORM\Column(type: Types::JSON)]
    public ?array $tags = [];

    #[ORM\Column(type: Types::BOOLEAN)]
    public bool $published = false;

    #[ORM\Column(type: Types::INTEGER)]
    public int $views = 0;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'author_uuid', referencedColumnName: 'uuid', nullable: false)]
    public ?User $author = null;

    #[ORM\Column(type: Types::STRING, unique: true, nullable: true)]
    private ?string $slug = null;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'contents')]
    #[ORM\JoinTable(
        name: 'content_tags',
        joinColumns: [
            new ORM\JoinColumn(name: 'content_uuid', referencedColumnName: 'uuid', onDelete: 'CASCADE')
        ],
        inverseJoinColumns: [
            new ORM\JoinColumn(name: 'tag_uuid', referencedColumnName: 'uuid', onDelete: 'CASCADE')
        ]
    )]
    private Collection $tagsCollection;


    public function __construct()
    {
        $this->tagsCollection = new ArrayCollection();
        $this->defineUuid();
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function setCover(?string $cover): self
    {
        $this->cover = $cover;

        return $this;
    }

    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }

    public function setMetaTitle(?string $metaTitle): self
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function setMetaDescription(?string $metaDescription): self
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    public function getTags(): ?array
    {
        return $this->tags;
    }

    public function setTags(array $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function getPublished(): bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): self
    {
        $this->published = $published;

        return $this;
    }

    public function getViews(): int
    {
        return $this->views;
    }

    public function setViews(int $views): self
    {
        $this->views = $views;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getTagsCollection(): Collection
    {
        return $this->tagsCollection;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tagsCollection->contains($tag)) {
            $this->tagsCollection[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tagsCollection->removeElement($tag);

        return $this;
    }
}

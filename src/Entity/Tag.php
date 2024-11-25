<?php declare(strict_types=1);

namespace App\Entity;

use App\Doctrine\Trait\UuidTrait;
use App\Doctrine\Trait\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'tag')]
#[ApiResource]
class Tag
{
    use UuidTrait;
    use TimestampableTrait;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Content::class, mappedBy: 'tagsCollection')]
    private Collection $contents;

    public function __construct()
    {
        $this->contents = new ArrayCollection();
        $this->defineUuid(); // Génère un UUID unique
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getContents(): Collection
    {
        return $this->contents;
    }

    public function addContent(Content $content): self
    {
        if (!$this->contents->contains($content)) {
            $this->contents[] = $content;
        }
        return $this;
    }

    public function removeContent(Content $content): self
    {
        $this->contents->removeElement($content);
        return $this;
    }
}

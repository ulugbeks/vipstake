<?php

namespace App\Entity\Frontend;

use App\Attribute\Admin\Autotranslate;
use App\Entity\Admin\User;
use App\Interface\FieldsGroupInterface;
use App\Interface\NavInterface;
use App\Interface\StatusInterface;
use App\Repository\Frontend\ServiceRepository;
use App\Trait\Frontend\PageMetaTrait;
use App\Trait\Frontend\SeoMetaTrait;
use App\Trait\Frontend\SeoTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Mapping\Annotation\Timestampable;
use Knp\DoctrineBehaviors\Contract\Entity\BlameableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\SoftDeletableInterface;
use Knp\DoctrineBehaviors\Model\Blameable\BlameableTrait;
use Knp\DoctrineBehaviors\Model\SoftDeletable\SoftDeletableTrait;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
class Service implements BlameableInterface,
    StatusInterface, FieldsGroupInterface, NavInterface
{
    use BlameableTrait;
    use SeoMetaTrait;
    use PageMetaTrait;
    use SeoTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'services')]
    private ?User $author = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $thumbnail = null;

    #[ORM\Column(length: 255)]
    #[Autotranslate]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Autotranslate]
    private ?string $content = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Gedmo\Slug(fields: ['title'], updatable: true, separator: '-', unique: false)]
    #[Autotranslate]
    private ?string $slug = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $deletedAt = null;

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

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string|null $slug
     */
    public function setSlug(?string $slug): void
    {
        $this->slug = $slug;
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

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?string $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getNavLabel(): string
    {
       return $this->getTitle();
    }

    /**
     * @return \DateTime|null
     */
    public function getDeletedAt(): ?\DateTime
    {
        return $this->deletedAt;
    }

    /**
     * @param \DateTime|null $deletedAt
     */
    public function setDeletedAt(?\DateTime $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }
}

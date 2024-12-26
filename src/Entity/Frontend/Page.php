<?php

namespace App\Entity\Frontend;

use App\Attribute\Admin\Autotranslate;
use App\Entity\Admin\User;
use App\Interface\FieldsGroupInterface;
use App\Interface\NavInterface;
use App\Interface\StatusInterface;
use App\Repository\Frontend\PageRepository;
use App\Trait\Frontend\PageMetaTrait;
use App\Trait\Frontend\SeoMetaTrait;
use App\Trait\Frontend\SeoTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Contract\Entity\BlameableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\SoftDeletableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Blameable\BlameableTrait;
use Knp\DoctrineBehaviors\Model\SoftDeletable\SoftDeletableTrait;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;

#[ORM\Entity(repositoryClass: PageRepository::class)]
class Page implements BlameableInterface,
    StatusInterface, FieldsGroupInterface, NavInterface
{
    use BlameableTrait;
    use SoftDeletableTrait;
    use SeoMetaTrait;
    use PageMetaTrait;
    use SeoTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'pages')]
    private ?User $author = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $thumbnail = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $template = null;

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


    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function setTemplate(?string $template): self
    {
        $this->template = $template;

        return $this;
    }

    public function getNavLabel(): string
    {
       return $this->getTitle();
    }
}

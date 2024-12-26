<?php

namespace App\Entity\Frontend;

use App\Attribute\Admin\Autotranslate;
use App\Repository\Frontend\SeoTemplatesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeoTemplatesRepository::class)]
class SeoTemplates
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $entity = null;

    #[ORM\Column(length: 255)]
    #[Autotranslate]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Autotranslate]
    private ?string $description = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntity(): ?string
    {
        return $this->entity;
    }

    public function setEntity(string $entity): self
    {
        $this->entity = $entity;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}

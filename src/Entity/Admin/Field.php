<?php

namespace App\Entity\Admin;

use App\Repository\Admin\FieldRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FieldRepository::class)]
class Field
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $type = null;

    #[ORM\ManyToOne(inversedBy: 'fields')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FieldsGroup $fieldGroup = null;

    #[ORM\Column]
    private ?int $position = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'related')]
    private ?self $parent = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class, orphanRemoval: true)]
    #[ORM\OrderBy(['position' => 'ASC'])]
    private Collection $related;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $parentLabel = null;

    #[ORM\OneToMany(mappedBy: 'field', targetEntity: Meta::class, orphanRemoval: true)]
    private Collection $metas;
    public function __toString()
    {
        return $this->getLabel();
    }

    public function __construct()
    {
        $this->related = new ArrayCollection();
        $this->metas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getFieldGroup(): ?FieldsGroup
    {
        return $this->fieldGroup;
    }

    public function setFieldGroup(?FieldsGroup $fieldGroup): self
    {
        $this->fieldGroup = $fieldGroup;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
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

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getRelated(): Collection
    {
        return $this->related;
    }

    public function addRelated(self $related): self
    {
        if (!$this->related->contains($related)) {
            $this->related->add($related);
            $related->setParent($this);
        }

        return $this;
    }

    public function removeRelated(self $related): self
    {
        if ($this->related->removeElement($related)) {
            // set the owning side to null (unless already changed)
            if ($related->getParent() === $this) {
                $related->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getParentLabel(): ?string
    {
        return $this->parentLabel;
    }

    /**
     * @param string $parentLabel
     */
    public function setParentLabel(?string $parentLabel): self
    {
        $this->parentLabel = $parentLabel;

        return $this;
    }

    /**
     * @return Collection<int, Meta>
     */
    public function getMetas(): Collection
    {
        return $this->metas;
    }

    public function addMeta(Meta $meta): self
    {
        if (!$this->metas->contains($meta)) {
            $this->metas->add($meta);
            $meta->setField($this);
        }

        return $this;
    }

    public function removeMeta(Meta $meta): self
    {
        if ($this->metas->removeElement($meta)) {
            // set the owning side to null (unless already changed)
            if ($meta->getField() === $this) {
                $meta->setField(null);
            }
        }

        return $this;
    }
}

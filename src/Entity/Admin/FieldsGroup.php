<?php

namespace App\Entity\Admin;

use App\Repository\Admin\FieldsGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FieldsGroupRepository::class)]
class FieldsGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $ruleType = null;

    #[ORM\Column(length: 255)]
    private ?string $ruleValue = null;

    #[ORM\OneToMany(mappedBy: 'fieldGroup', targetEntity: Field::class, orphanRemoval: true)]
    #[ORM\OrderBy(['position' => "ASC"])]
    private Collection $fields;

    public function __construct()
    {
        $this->fields = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getRuleType(): ?string
    {
        return $this->ruleType;
    }

    public function setRuleType(string $ruleType): self
    {
        $this->ruleType = $ruleType;

        return $this;
    }

    public function getRuleValue(): ?string
    {
        return $this->ruleValue;
    }

    public function setRuleValue(string $ruleValue): self
    {
        $this->ruleValue = $ruleValue;

        return $this;
    }

    /**
     * @return Collection<int, Field>
     */
    public function getFields(): Collection
    {
        return $this->fields;
    }

    public function addField(Field $field): self
    {
        if (!$this->fields->contains($field)) {
            $this->fields->add($field);
            $field->setFieldGroup($this);
        }

        return $this;
    }

    public function removeField(Field $field): self
    {
        if ($this->fields->removeElement($field)) {
            // set the owning side to null (unless already changed)
            if ($field->getFieldGroup() === $this) {
                $field->setFieldGroup(null);
            }
        }

        return $this;
    }
}

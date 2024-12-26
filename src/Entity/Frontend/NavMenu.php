<?php

namespace App\Entity\Frontend;

use App\Repository\Frontend\NavMenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;

#[ORM\Entity(repositoryClass: NavMenuRepository::class)]
class NavMenu {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'navMenu', targetEntity: NavItem::class, orphanRemoval: true)]
    #[ORM\OrderBy(['position' => "ASC"])]
    private Collection $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, NavItem>
     */
    public function getItems(): Collection
    {
        return $this->items->filter(function (NavItem $item){
            return $item->getParentItem() == null;
        });
//        return $this->items;
    }

    public function getAllItems(): Collection
    {
        return $this->items;
    }

    public function addItem(NavItem $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setNavMenu($this);
        }

        return $this;
    }

    public function removeItem(NavItem $item): self
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getNavMenu() === $this) {
                $item->setNavMenu(null);
            }
        }

        return $this;
    }
}

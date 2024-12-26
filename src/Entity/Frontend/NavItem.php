<?php

namespace App\Entity\Frontend;

use App\Repository\Frontend\NavItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NavItemRepository::class)]
class NavItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $entityType = null;

    #[ORM\Column(nullable: true)]
    private ?int $entityId = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'navItems')]
    private ?self $parentItem = null;

    #[ORM\OneToMany(mappedBy: 'parentItem', targetEntity: self::class, orphanRemoval: true)]
    #[ORM\OrderBy(['position' => "ASC"])]
    private Collection $navItems;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $url = null;

    #[ORM\Column]
    private ?int $position = null;

    #[ORM\ManyToOne(inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private ?NavMenu $navMenu = null;

    public function __construct()
    {
        $this->navItems = new ArrayCollection();
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

    public function getEntityType(): ?string
    {
        return $this->entityType;
    }

    public function setEntityType(string $entityType): self
    {
        $this->entityType = $entityType;

        return $this;
    }

    public function getEntityId(): ?int
    {
        return $this->entityId;
    }

    public function setEntityId(int $entityId): self
    {
        $this->entityId = $entityId;

        return $this;
    }

    public function getParentItem(): ?self
    {
        return $this->parentItem;
    }

    public function setParentItem(?self $parentItem): self
    {
        $this->parentItem = $parentItem;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getNavItems(): Collection
    {
        return $this->navItems;
    }

    public function addNavItem(self $navItem): self
    {
        if (!$this->navItems->contains($navItem)) {
            $this->navItems->add($navItem);
            $navItem->setParentItem($this);
        }

        return $this;
    }

    public function removeNavItem(self $navItem): self
    {
        if ($this->navItems->removeElement($navItem)) {
            // set the owning side to null (unless already changed)
            if ($navItem->getParentItem() === $this) {
                $navItem->setParentItem(null);
            }
        }

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

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

    public function getNavMenu(): ?NavMenu
    {
        return $this->navMenu;
    }

    public function setNavMenu(?NavMenu $navMenu): self
    {
        $this->navMenu = $navMenu;

        return $this;
    }
}

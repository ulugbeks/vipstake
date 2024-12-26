<?php

namespace App\Trait\Frontend;

use App\Attribute\Admin\Autotranslate;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait SeoTrait
{
    #[ORM\Column(length: 255, nullable: true)]
    #[Autotranslate]
    private ?string $h1 = null;
    #[ORM\Column(length: 255, nullable: true)]
    #[Autotranslate]
    private ?string $seoTitle = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Autotranslate]
    private ?string $seoDescription = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $canonical = null;

    /**
     * @param string|null $seoDescription
     * @return SeoTrait
     */
    public function setSeoDescription(?string $seoDescription): self
    {
        $this->seoDescription = $seoDescription;
        return $this;
    }

    /**
     * @param string|null $seoTitle
     * @return SeoTrait
     */
    public function setSeoTitle(?string $seoTitle): self
    {
        $this->seoTitle = $seoTitle;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSeoTitle(): ?string
    {
        return $this->seoTitle;
    }

    /**
     * @return string|null
     */
    public function getSeoDescription(): ?string
    {
        return $this->seoDescription;
    }

    /**
     * @param string|null $canonical
     * @return SeoTrait
     */
    public function setCanonical(?string $canonical): self
    {
        $this->canonical = $canonical;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCanonical(): ?string
    {
        return $this->canonical;
    }

    /**
     * @param string|null $h1
     * @return SeoTrait
     */
    public function setH1(?string $h1): self
    {
        $this->h1 = $h1;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getH1(): ?string
    {
        return $this->h1;
    }
}
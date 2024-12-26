<?php

namespace App\Trait\Frontend;

use Doctrine\ORM\Mapping as ORM;

trait SeoMetaTrait
{
    #[ORM\Column]
    private ?bool $noindex = false;

    #[ORM\Column]
    private ?bool $nofollow = false;

    public function isNoindex(): ?bool
    {
        return $this->noindex;
    }

    public function setNoindex(bool $noindex): self
    {
        $this->noindex = $noindex;

        return $this;
    }

    public function isNofollow(): ?bool
    {
        return $this->nofollow;
    }

    public function setNofollow(bool $nofollow): self
    {
        $this->nofollow = $nofollow;

        return $this;
    }
}
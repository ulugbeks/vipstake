<?php

namespace App\Trait\Frontend;

use App\Interface\StatusInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Timestampable;

trait PageMetaPropertiesTrait
{
    #[ORM\Column(length: 255)]
    private ?string $status = StatusInterface::PUBLISH;
    #[ORM\Column(nullable: true)]
    #[Timestampable(on: "create")]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Timestampable(on: "update")]
    private ?\DateTime $updatedAt = null;
}
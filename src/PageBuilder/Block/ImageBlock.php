<?php

namespace App\PageBuilder\Block;

use App\PageBuilder\Fields\ImageField;
use App\PageBuilder\Fields\TextField;
use App\PageBuilder\Interface\BlockInterface;

class ImageBlock extends AbstractBlock implements BlockInterface
{
    private string $id;

    public function __construct()
    {
        $this->id = uniqid('block_', true);
    }

    public function getName(): string
    {
        return 'image';
    }

    public function getLabel(): string
    {
        return 'Image Block';
    }

    public function getFields(): array
    {
        return [
            new ImageField('img'),
        ];
    }

    public function getAssets(array $assets): array
    {
        return [

        ];
    }

    public function getTemplate(): string
    {
        return '/blocks/image/image.html.twig';
    }

    public function getArea(): string
    {
        return BlockInterface::AREA_TEXT;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
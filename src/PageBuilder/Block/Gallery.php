<?php

namespace App\PageBuilder\Block;

use App\PageBuilder\Fields\ImageField;
use App\PageBuilder\Fields\TextField;
use App\PageBuilder\Interface\BlockInterface;

class Gallery extends AbstractBlock implements BlockInterface
{
    private string $id;

    public function __construct()
    {
        $this->id = uniqid('block_', true);
    }

    public function getName(): string
    {
        return 'gallery';
    }

    public function getLabel(): string
    {
        return 'Gallery';
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
        return '/blocks/gallery/gallery.html.twig';
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
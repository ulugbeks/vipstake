<?php

namespace App\PageBuilder\Block;

use App\PageBuilder\Fields\ImageField;
use App\PageBuilder\Fields\TextField;
use App\PageBuilder\Interface\BlockInterface;

class IconLink extends AbstractBlock implements BlockInterface
{
    private string $id;

    public function __construct()
    {
        $this->id = uniqid('block_', true);
    }

    public function getName(): string
    {
        return 'icon_link';
    }

    public function getLabel(): string
    {
        return 'Link with icon';
    }

    public function getFields(): array
    {
        return [
            new ImageField('img'),
            new TextField('a'),
        ];
    }

    public function getAssets(array $assets): array
    {
        return [

        ];
    }

    public function getTemplate(): string
    {
        return '/blocks/icon_link/icon_link.html.twig';
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
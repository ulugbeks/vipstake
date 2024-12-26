<?php

namespace App\PageBuilder\Block;

use App\PageBuilder\Fields\ListField;
use App\PageBuilder\Fields\TextField;
use App\PageBuilder\Interface\BlockInterface;

class Ol extends AbstractBlock implements BlockInterface
{
    private string $id;

    public function __construct()
    {
        $this->id = uniqid('block_', true);
    }

    public function getName(): string
    {
        return 'ol';
    }

    public function getLabel(): string
    {
        return 'Ordered List';
    }

    public function getFields(): array
    {
        return [
            new ListField('ol', 'Enter text'),
        ];
    }

    public function getAssets(array $assets): array
    {
        return [

        ];
    }

    public function getTemplate(): string
    {
        return '/blocks/ol/ol.html.twig';
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
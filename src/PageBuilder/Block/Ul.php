<?php

namespace App\PageBuilder\Block;

use App\PageBuilder\Fields\ListField;
use App\PageBuilder\Fields\TextField;
use App\PageBuilder\Interface\BlockInterface;

class Ul extends AbstractBlock implements BlockInterface
{
    private string $id;

    public function __construct()
    {
        $this->id = uniqid('block_', true);
    }

    public function getName(): string
    {
        return 'ul';
    }

    public function getLabel(): string
    {
        return 'Unordered List';
    }

    public function getFields(): array
    {
        return [
            new ListField('ul', 'Enter text'),
        ];
    }

    public function getAssets(array $assets): array
    {
        return [

        ];
    }

    public function getTemplate(): string
    {
        return '/blocks/ul/ul.html.twig';
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
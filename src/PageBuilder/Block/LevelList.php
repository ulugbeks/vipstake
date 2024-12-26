<?php

namespace App\PageBuilder\Block;

use App\PageBuilder\Fields\ListField;
use App\PageBuilder\Fields\TextField;
use App\PageBuilder\Interface\BlockInterface;

class LevelList extends AbstractBlock implements BlockInterface
{
    private string $id;

    public function __construct()
    {
        $this->id = uniqid('block_', true);
    }

    public function getName(): string
    {
        return 'level_list';
    }

    public function getLabel(): string
    {
        return 'Level List';
    }

    public function getFields(): array
    {
        return [
            new TextField('heading', 'Enter text'),
            new TextField('text', 'Enter text')
        ];
    }

    public function getAssets(array $assets): array
    {
        return [

        ];
    }

    public function getTemplate(): string
    {
        return '/blocks/level_list/level_list.html.twig';
    }

    public function getPrototype(): string
    {
        return '/blocks/level_list/prototype.html.twig';
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
<?php

namespace App\PageBuilder\Block;

use App\PageBuilder\Fields\ListField;
use App\PageBuilder\Fields\TextField;
use App\PageBuilder\Interface\BlockInterface;
use Gedmo\Sluggable\Util\Urlizer;

class Divider extends AbstractBlock implements BlockInterface
{
    private string $id;

    public function __construct()
    {
        $this->id = uniqid('block_', true);
    }

    public function getName(): string
    {
        return 'divider';
    }

    public function getLabel(): string
    {
        return 'Divider';
    }

    public function getFields(): array
    {
        return [
            new TextField('hr')
        ];
    }

    public function getAssets(array $assets): array
    {
        return [

        ];
    }

    public function getTemplate(): string
    {
        return '/blocks/divider/divider.html.twig';
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
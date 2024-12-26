<?php

namespace App\PageBuilder\Block;

use App\PageBuilder\Fields\TextField;
use App\PageBuilder\Interface\BlockInterface;

class H2 extends AbstractBlock implements BlockInterface
{
    private string $id;

    public function __construct()
    {
        $this->id = uniqid('block_', true);
    }

    public function getName(): string
    {
        return 'h2';
    }

    public function getLabel(): string
    {
        return 'H2';
    }

    public function getFields(): array
    {
        return [
            new TextField('h', 'Enter text'),
        ];
    }

    public function getAssets(array $assets): array
    {
        return [

        ];
    }

    public function getTemplate(): string
    {
        return '/blocks/h2/h2.html.twig';
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
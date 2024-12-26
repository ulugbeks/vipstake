<?php

namespace App\PageBuilder\Block;

use App\PageBuilder\Fields\TextField;
use App\PageBuilder\Interface\BlockInterface;

class TextBlock extends AbstractBlock implements BlockInterface
{
    private string $id;

    public function __construct()
    {
        $this->id = uniqid('block_', true);
    }

    public function getName(): string
    {
        return 'text';
    }

    public function getLabel(): string
    {
        return 'Text Block';
    }

    public function getFields(): array
    {
        return [
            new TextField('p', 'Enter text'),
        ];
    }

    public function getAssets(array $assets): array
    {
        return [

        ];
    }

    public function getTemplate(): string
    {
        return '/blocks/textarea/textarea.html.twig';
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
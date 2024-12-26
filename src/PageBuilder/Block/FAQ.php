<?php

namespace App\PageBuilder\Block;

use App\PageBuilder\Fields\ListField;
use App\PageBuilder\Fields\TextField;
use App\PageBuilder\Interface\BlockInterface;

class FAQ extends AbstractBlock implements BlockInterface
{
    private string $id;

    public function __construct()
    {
        $this->id = uniqid('block_', true);
    }

    public function getName(): string
    {
        return 'faq';
    }

    public function getLabel(): string
    {
        return 'FAQ';
    }

    public function getFields(): array
    {
        return [
            new TextField('question', 'Enter text'),
            new TextField('answer', 'Enter text')
        ];
    }

    public function getAssets(array $assets): array
    {
        return [

        ];
    }

    public function getTemplate(): string
    {
        return '/blocks/faq/faq.html.twig';
    }

    public function getPrototype(): string
    {
        return '/blocks/faq/prototype.html.twig';
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
<?php

namespace App\PageBuilder\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Environment;

class PageBuilderService
{
    private array $layouts;
    private array $blocks;
    const TEMPLATES_DIR = '/page_builder';

    public function __construct(
        private readonly LayoutService $layoutService,
        private readonly BlockService $blockService,
        private readonly Environment $twig,
    ) {
        $this->layouts = $this->layoutService->getLayouts();
        $this->blocks = $this->blockService->getBlocks();
    }

    /**
     * @return array
     */
    public function getLayouts(): array
    {
        return $this->layouts;
    }

    public function getBlocks(): array
    {
        return $this->blocks;
    }

    public function renderLayout(string $layoutName)
    {
        foreach ($this->getLayouts() as $layout) {
            if ($layout->getName() === $layoutName) {
                $template = $layout->getTemplate();
                $rendered = $this->twig->render(self::TEMPLATES_DIR.$template, [
                    'id' => $layout->getId(),
                ]);

                return [
                    'id' => $layout->getId(),
                    'template' => $rendered,
                ];
            }
        }

        throw new \RuntimeException("Layout $layoutName template has not found");
    }

    public function renderBlock(string $blockName)
    {
        foreach ($this->getBlocks() as $block) {
            if ($block->getName() === $blockName) {
                $blockArray = $block->toArray();

                if($this->hasPrototype($block)){
                    $blockArray['prototype'] = $this->twig->render(self::TEMPLATES_DIR . $block->getPrototype());
                }

                $template = $block->getTemplate();
                $rendered = $this->twig->render(self::TEMPLATES_DIR.$template, [
                    'id' => $block->getId(),
                    'name' => $block->getName(),
                    'fields' => $blockArray['fields'],
                ]);

                return [
                    'block' => $blockArray,
                    'template' => $rendered,
                ];
            }
        }

        throw new \RuntimeException("Block $blockName template has not found");
    }

    private function hasPrototype(object $block)
    {
        return (new \ReflectionClass(get_class($block)))->hasMethod('getPrototype');
    }
}
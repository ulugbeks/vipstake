<?php

namespace App\PageBuilder\Service;

use Gedmo\Sluggable\Util\Urlizer;
use Twig\Environment;
use function Symfony\Component\Translation\t;

class PbRenderService
{
    private array $tableOfContents = [];
    private array $headingTags = ['h2', 'h3', 'h4'];

    public function __construct(
        private readonly Environment $twig
    ) {
    }

    public function render(mixed $content): array
    {
        if (json_validate($content)) {
            $content = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        }

        $html = $this->renderLayouts($content);

        return [
            'html' => $html,
            'toc' => $this->tableOfContents,
        ];
    }

    private function renderLayouts(array $content): string
    {
        $html = '';

        foreach ($content as $layoutData) {
            $columns = $this->renderBlocks($layoutData['columns']);

            $layout = $this->twig->render('frontend/pagebuilder/layouts/'.$layoutData['name'].'.html.twig', [
                'columns' => $columns,
            ]);

            $html .= $layout;
        }

        return $html;
    }

    private function renderBlocks(array $columns)
    {
        $blocks = [];

        foreach ($columns as $column) {
            $columnBlocks = [];

            foreach ($column['blocks'] as $blockData) {
                $block = $this->twig->render('frontend/pagebuilder/blocks/'.$blockData['name'].'.html.twig', [
                    'data' => $blockData['data'],
                ]);

                $columnBlocks[] = $block;

                if (in_array($blockData['name'], $this->headingTags)) {
                    $this->tableOfContents[] = [
                        'text' => $blockData['data']['text'],
                        'url' => Urlizer::urlize($blockData['data']['text'])
                    ];
                }
            }

            $blocks[] = $columnBlocks;
        }

        return $blocks;
    }
}
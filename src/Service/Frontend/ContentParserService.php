<?php

namespace App\Service\Frontend;

use Twig\Environment;

class ContentParserService
{

    public function __construct(
        private readonly Environment $twig,
        private readonly ShortcodeService $shortcodeService
    ) {
    }

    public function renderHTML(array $blocks): string
    {
        $html = '';

        foreach ($blocks as $block) {
            $html .= $this->renderBlock($block);
        }

        $html = $this->shortcodeService->replaceShortcodes($html);

        return str_replace('http:///', '/', $this->minifyHtml($html));
    }

    public function renderBlock($block): string
    {
        return $this->twig->render('frontend/blocks/'.$block['type'].'.html.twig', [
            'block' => $block,
        ]);
    }

    private function minifyHtml($html)
    {
        $search = array(
            '/(\n|^)(\x20+|\t)/',
            '/(\n|^)\/\/(.*?)(\n|$)/',
            '/\n/',
            '/\<\!--.*?-->/',
            '/(\x20+|\t)/', # Delete multispace (Without \n)
            '/\>\s+\</', # strip whitespaces between tags
            '/(\"|\')\s+\>/', # strip whitespaces between quotation ("') and end tags
            '/=\s+(\"|\')/',
        ); # strip whitespaces between = "'

        $replace = array(
            "\n",
            "\n",
            " ",
            "",
            " ",
            "><",
            "$1>",
            "=$1",
        );

        $html = preg_replace($search, $replace, $html);

        return str_replace(['<br>'], [''], $html);
    }

    public function renderTechPageContent(mixed $content)
    {
        $html = '';

        if (json_validate($content)) {
            $content = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        }

        $grouped = $this->groupBlocksByHeading($content['blocks']);

        foreach ($grouped as $item) {
            $html .= $this->twig->render('frontend/technical/item.html.twig', [
                'data' => $item
            ]);
        }

        return $html;
    }

    private function groupBlocksByHeading(array $blocks): array
    {
        $groupedBlocks = [];
        $currentGroup = null;

        foreach ($blocks as $block) {
            if ($block['type'] === 'header') {
                if ($currentGroup !== null) {
                    $groupedBlocks[] = $currentGroup;
                }
                $currentGroup = [
                    'heading' => $block['data']['text'],
                    'content' => [],
                ];
            } else {
                if ($currentGroup !== null) {
                    $currentGroup['content'][] = $this->renderBlock($block);
                }
            }
        }

        if ($currentGroup !== null) {
            $groupedBlocks[] = $currentGroup;
        }

        return $groupedBlocks;
    }
}
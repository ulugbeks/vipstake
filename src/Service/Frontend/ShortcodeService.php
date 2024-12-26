<?php

namespace App\Service\Frontend;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Twig\Environment;

class ShortcodeService
{
    public function __construct(
        private readonly Environment $twig,
        private readonly ParameterBagInterface $container
    ) {
    }

    public static function extractShortcodes($content)
    {
        $pattern = '/\[(\w+)([^\]]*)\]/';
        preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);

        $shortcodes = array();
        foreach ($matches as $match) {
            $shortcodeName = $match[1];
            $attributesStr = $match[2];
            $attributes = self::parseAttributes($attributesStr);

            $shortcodes[] = array(
                'name' => $shortcodeName,
                'attributes' => $attributes,
            );
        }

        return $shortcodes;
    }

    private static function parseAttributes($attributesStr)
    {
        $attributesStr = html_entity_decode($attributesStr);
        $pattern = '/(\w+)="([^"]*)"/';
        preg_match_all($pattern, $attributesStr, $matches, PREG_SET_ORDER);

        $attributes = array();
        foreach ($matches as $match) {
            $attributeName = $match[1];
            $attributeValue = $match[2];
            $attributes[$attributeName] = $attributeValue;
        }

        return $attributes;
    }

    public function replaceShortcodes($content)
    {
        $content = html_entity_decode($content);
        $content = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $content);
        $fs = new Filesystem();
        $shortcodes = ShortcodeService::extractShortcodes($content);

        foreach ($shortcodes as $shortcode) {
            $shortcodeName = $shortcode['name'];
            $attributes = $shortcode['attributes'];
            $replacementContent = ''; // Здесь должен быть ваш код для вычисления результата

            $templatePath = $this->container->get('kernel.project_dir') . '/templates/frontend/shortcodes/'.$shortcodeName.'.html.twig';
            $template = 'frontend/shortcodes/'.$shortcodeName.'.html.twig';

            if ($fs->exists($templatePath)) {
                $replacementContent = $this->twig->render($template, ['attributes' => $attributes]);
            }

            $shortcodeString = "[$shortcodeName";
            foreach ($attributes as $attributeName => $attributeValue) {
                $shortcodeString .= " $attributeName=\"$attributeValue\"";
            }

            $shortcodeString .= "]";
            $content = str_replace($shortcodeString, $replacementContent, $content);
        }

        return $content;
    }
}
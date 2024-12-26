<?php

namespace App\PageBuilder\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class PageBuilderExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
          new TwigFunction('uniqueid', [PageBuilderRuntime::class, 'uniqueid'])
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('urlize', [PageBuilderRuntime::class, 'urlize'])
        ];
    }
}
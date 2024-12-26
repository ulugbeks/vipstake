<?php

namespace App\PageBuilder\Twig;

use Gedmo\Sluggable\Util\Urlizer;
use Twig\Extension\RuntimeExtensionInterface;

class PageBuilderRuntime implements RuntimeExtensionInterface
{
    public function uniqueid($prefix = null)
    {
        return uniqid($prefix, true);
    }

    public function urlize(string $string)
    {
        return Urlizer::urlize($string);
    }
}
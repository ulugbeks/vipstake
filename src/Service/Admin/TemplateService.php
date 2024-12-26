<?php

namespace App\Service\Admin;

use App\Kernel;
use Symfony\Component\Finder\Finder;

class TemplateService
{
    public function findTemplates()
    {
        $templates = ['Default' => null];
        $finder = new Finder();
        $kernel = new Kernel('dev', false);
        $dir = $kernel->getProjectDir() . '/templates';

        $files = $finder->files()->in($dir)->name('*.twig');;

        foreach ($files as $file) {
            $templateName = $this->parseTwigComments($file->getContents());
            if($templateName){
                $templates[$templateName] = $file->getRelativePathname();
            }
        }

        return $templates;
    }

    public function parseTwigComments(string $content): mixed
    {
        preg_match_all('/\{\#(.*?)Template:(.*?)(\s|\S)*?\#\}/s', $content, $matches);

        $comments = array_map(function ($comment) {
            return trim(preg_replace('/^\{\#|\.\s*\#\}$/', '', str_replace('Template:', '', $comment)));
        }, $matches[0]);

        return $comments[0] ?? null;
    }
}
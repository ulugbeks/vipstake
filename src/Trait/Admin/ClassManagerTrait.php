<?php

namespace App\Trait\Admin;

use App\Kernel;
use Symfony\Component\Finder\Finder;

trait ClassManagerTrait
{
    private function getClasses(){
        $kernel = new Kernel('dev', false);
        $dir = $kernel->getProjectDir();

        $finder = new Finder();
        $finder->files()->in($dir . '/src/Controller/Admin')->name('*.php');

        $classes = [];
        foreach ($finder as $file) {
            $class = 'App\\Controller\\Admin\\' . substr($file->getFilename(), 0, -4);
            $classes[] = $class;
        }

        return $classes;
    }

    private function getSubclasses(string $parentClass){
        $childClasses = [];
        $classes = $this->getClasses();

        foreach ($classes as $class) {
            if (is_subclass_of($class, $parentClass)) {
                $childClasses[] = $class;
            }
        }

        return $childClasses;
    }
}
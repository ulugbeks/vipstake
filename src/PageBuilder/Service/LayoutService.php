<?php

namespace App\PageBuilder\Service;

use App\PageBuilder\Interface\LayoutInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

readonly class LayoutService
{
    public function __construct(
        private ParameterBagInterface $parameterBag,
    ) {
    }

    public function getLayouts()
    {
        $layouts = [];

        $layoutClasses = ClassFinderService::getClassesImplementingInterface(
            $this->parameterBag->get('kernel.project_dir').'/src/PageBuilder',
            LayoutInterface::class
        );

        foreach ($layoutClasses as $class) {
            $layouts[] = (new \ReflectionClass($class))->newInstance();
        }

        return $layouts;
    }
}
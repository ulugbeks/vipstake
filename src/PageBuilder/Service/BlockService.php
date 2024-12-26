<?php

namespace App\PageBuilder\Service;

use App\PageBuilder\Interface\BlockInterface;
use App\PageBuilder\Interface\LayoutInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class BlockService
{
    public function __construct(
        private ParameterBagInterface $parameterBag,
    ) {
    }

    /**
     * @throws \ReflectionException
     */
    public function getBlocks()
    {
        $layouts = [];

        $layoutClasses = ClassFinderService::getClassesImplementingInterface(
            $this->parameterBag->get('kernel.project_dir').'/src/PageBuilder',
            BlockInterface::class
        );

        foreach ($layoutClasses as $class) {
            $layouts[] = (new \ReflectionClass($class))->newInstance();
        }

        return $layouts;
    }
}
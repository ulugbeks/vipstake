<?php

namespace App\InputType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ThumbnailType extends AbstractType
{
    public function getParent()
    {
        return FileType::class;
    }
}
<?php

namespace App\InputType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class FiltersType extends AbstractType
{
    public function getParent()
    {
        return ChoiceType::class;
    }
}
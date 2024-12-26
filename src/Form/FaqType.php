<?php

namespace App\Form;

use App\Field\RepeaterFieldType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class FaqType extends AbstractType
{
    public function getParent(): string
    {
        return CollectionType::class;
    }
}
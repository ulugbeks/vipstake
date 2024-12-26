<?php

namespace App\Form;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use App\Entity\Frontend\ProfileFilterVariant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VariantsCollectionType extends AbstractType
{
    public function getParent()
    {
        return CollectionType::class;
    }
}

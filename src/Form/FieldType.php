<?php

namespace App\Form;

use App\Entity\Admin\Field;
use App\InputType\FieldSetType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           ->add('fieldSet', CollectionType::class, [
               'entry_type' => FieldSetType::class,
               'allow_add' => true,
               'allow_delete' => true,
               'by_reference' => false,
               'label' => false,
               'attr' => [
                   'class' => 'fieldSet-collection',
               ],
               'prototype_options' => [
                   'label' => false,
                   'attr' => [
                       'class' => 'field-row'
                   ]
               ],
           ])
        ;
    }
}

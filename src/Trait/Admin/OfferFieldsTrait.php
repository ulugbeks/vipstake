<?php

namespace App\Trait\Admin;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

trait OfferFieldsTrait
{
    public function addOfferFields(FormBuilderInterface $builder)
    {
        $builder->add('offerHeading', TextType::class, [
            'label' => 'Offer Heading',
        ])
            ->add('offerText', TextareaType::class, [
                'label' => 'Offer text',
            ])
            ->add('offerButton', TextType::class, [
                'label' => 'Offer Button Text',
            ]);
    }
}
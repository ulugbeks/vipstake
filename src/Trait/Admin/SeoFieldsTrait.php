<?php

namespace App\Trait\Admin;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

trait SeoFieldsTrait
{
    public function addSeoFields(FormBuilderInterface $builder)
    {
        $builder->add('noindex', CheckboxType::class, [
            'label' => 'No index',
            'required' => false,
        ])
            ->add('nofollow', CheckboxType::class, [
                'label' => 'No follow',
                'required' => false,
            ])
            ->add('h1', TextType::class, [
                'label' => 'H1',
                'required' => false,
            ])
            ->add('seoTitle', TextType::class, [
                'label' => 'SEO Title',
                'required' => false,
            ])
            ->add('seoDescription', TextareaType::class, [
                'label' => 'SEO Description',
                'required' => false,
            ])
            ->add('canonical', TextType::class, [
                'label' => 'Canonical',
                'required' => false,
            ])
        ;
    }
}
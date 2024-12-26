<?php

namespace App\Form;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use App\Entity\Frontend\NavMenu;
use App\InputType\NavType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NavMenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name'
            ])
            ->add('items', NavType::class, [
                'label' => 'Select pages to add',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => NavMenu::class,
        ]);
    }
}

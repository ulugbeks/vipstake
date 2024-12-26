<?php

namespace App\Form;

use App\Entity\Admin\FieldsGroup;
use App\InputType\FieldsRelationType;
use App\Interface\FieldsGroupInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FieldsGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Field group name'
            ])
            ->add('ruleType', ChoiceType::class, [
                'label' => 'Related to',
                'choices' => [
                    'Template' => FieldsGroupInterface::RULE_TEMPLATE,
                    'Entity type' => FieldsGroupInterface::RULE_ENTITY
                ],
            ])
            ->add('ruleValue', FieldsRelationType::class,[
                'label' => 'Is equal to',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FieldsGroup::class,
        ]);
    }
}

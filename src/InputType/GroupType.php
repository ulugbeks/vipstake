<?php

namespace App\InputType;

use App\Entity\Admin\Field;
use App\Interface\FieldInterface;
use App\Registry\FieldRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, [
                'label' => 'Field type',
                'choices' => $this->getTypeChoices(),
            ])
            ->add('name', TextType::class, [
                'label' => 'Name',
                'attr' => ['class' => 'fieldset__name']
            ])
            ->add('label', TextType::class, [
                'label' => 'Field key',
                'attr' => ['class' => 'fieldset__label']
            ])
            ->add('position', HiddenType::class, [
                'attr' => [
                    'class' => '--hidden'
                ]
            ])
            ->add('parentLabel', HiddenType::class, [
                'attr' => [
                    'class' => '--hidden'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Field::class,
        ]);
    }

    private function getTypeChoices(){
        $choices = [];
        $fields = FieldRegistry::getFields();

        foreach ($fields as $field){
            $class = new \ReflectionClass($field);

            if($class->implementsInterface(FieldInterface::class)){
                $type = ucfirst($class->getMethod('getType')->invoke($class->newInstance()));
                $choices[$type] = $class->getName();
            }
        }

        return $choices;
    }
}
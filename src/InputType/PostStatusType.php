<?php

namespace App\InputType;

use App\Interface\StatusInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostStatusType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => [
                'Publish' => StatusInterface::PUBLISH,
                'Draft' => StatusInterface::DRAFT,
                'Private' => StatusInterface::PRIVATE,
            ],
        ]);
    }
    public function getParent()
    {
        return ChoiceType::class;
    }
}
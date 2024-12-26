<?php

namespace App\InputType;

use App\Service\Admin\TemplateService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TemplateType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $templateService = new TemplateService();
        $templates = $templateService->findTemplates();

        $resolver->setDefaults([
            'choices' => $templates,
        ]);
    }
    public function getParent()
    {
        return ChoiceType::class;
    }
}
<?php

namespace App\InputType;

use App\Interface\FieldsGroupInterface;
use App\Service\Admin\FieldsService;
use App\Service\Admin\TemplateService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FieldsRelationType extends AbstractType
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $templates = (new TemplateService())->findTemplates();
        $entities = (new FieldsService())->getEntitiesWithFields($this->em);
        $resolver->setDefaults([
            'choices' => [
                FieldsGroupInterface::RULE_TEMPLATE => $templates,
                FieldsGroupInterface::RULE_ENTITY => $entities
            ],
            'choice_value' => function ($choice) {
                return $choice === null ? 'default' : $choice;
            }
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}
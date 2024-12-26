<?php

namespace App\InputType;

use App\Interface\NavInterface;
use App\Service\Admin\NavItemService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NavType extends AbstractType
{
    private EntityManagerInterface $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $entityList = [];
        $entities = NavItemService::getEntityTypes();

        foreach ($entities as $key => $entity){
            /** If entity implements nav interface then add it to nav choices */
            if((new \ReflectionClass($entity['class']))->implementsInterface(NavInterface::class)){
                $repository = $this->em->getRepository($entity['class']);
                $list = $repository->findAll();

                foreach ($list as $item){
                    /** Method from NavInterface. */
                    $label = $item->getNavLabel();
                    $entityList[$key][$label] = $key . ' ' .  $item->getId();
                }
            }
        }

        $resolver->setDefaults([
            'choices' => $entityList,
            'mapped' => false,
            'required' => false,
            'constraints' => [],
            'validation_groups' => false,
        ]);
    }
    public function getParent()
    {
        return ChoiceType::class;
    }
}
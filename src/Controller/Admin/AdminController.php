<?php

namespace App\Controller\Admin;

use App\Entity\Admin\Field;
use App\Entity\Admin\FieldsGroup;
use App\Entity\Admin\Meta;
use App\Field\FieldItem;
use App\Field\FieldsForm;
use App\Repository\Admin\MetaRepository;
use App\Service\Admin\FieldsBuilder;
use App\Service\Admin\FieldsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    protected $em;
    protected $metaRepository;
    protected $fieldsService;

    public function __construct(
        EntityManagerInterface $em,
        MetaRepository $metaRepository,
        FieldsService $fieldsService
    ) {
        $this->em = $em;
        $this->metaRepository = $metaRepository;
        $this->fieldsService = $fieldsService;
    }


    private function createFieldsFormView(?FieldsGroup $group, $data = []): FieldsForm
    {
        return (new FieldsBuilder())->create($group, $data);
    }

    protected function handleFields(FieldsForm $fieldsForm, $meta, $entity): void
    {
        if (array_key_exists('faq', $meta)) {
            $this->deleteDuplicates($entity, $entity->getId(), 'faq');
        }


        $fields = $fieldsForm->handleData($meta, $entity, $this->em);

        foreach ($fieldsForm->getFields() as $field) {
            /** @var FieldItem $field */
            if (!array_key_exists($field->getVar('array_map'), $meta)) {
                $this->removeMetaWithChildren($field->getField(), $entity);
            }
        }

        foreach ($fields as $meta) {
            /** @var Meta $meta */
            $meta->setEntityType($this->getOriginalClass($meta->getEntityType()));
            $this->em->persist($meta);
        }

        $this->em->flush();
    }

    private function removeMetaWithChildren(Field $field, $entity)
    {
        $metaRepository = $this->em->getRepository(Meta::class);

        if (!$field->getRelated()->isEmpty()) {
            foreach ($field->getRelated() as $item){
                $this->removeMetaWithChildren($item, $entity);
            }
        }

        $metaRepository->delete($field->getId(), $entity->getId(), $this->getOriginalClass(get_class($entity)));
        $this->em->flush();
    }

    protected function createFieldsForm($entity): FieldsForm|null
    {
        $class = $this->em->getClassMetadata(get_class($entity))->name;

        $meta = $this->metaRepository->findFields($class, $entity->getId());
        $fields = $this->fieldsService->getMetaFields($entity, $this->em->getRepository(FieldsGroup::class), $class);

        return $fields->isEmpty() ? null : $this->createFieldsFormView($fields->get(0), $meta);
    }

    private function getOriginalClass($proxy){
       return $this->em->getClassMetadata($proxy)->name;
    }

    public function deleteDuplicates($entity, $id, $label)
    {
        $metaRepository = $this->em->getRepository(Meta::class);
        $metaRepository->findDuplicates(get_class($entity), $id, $label);
    }
}
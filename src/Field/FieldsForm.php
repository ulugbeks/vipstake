<?php

namespace App\Field;

use App\Entity\Admin\Meta;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
use Twig\Environment;

class FieldsForm
{
    private ArrayCollection $fields;
    private ArrayCollection $data;
    private ArrayCollection $metaCollection;
    private object $entity;
    private ?array $meta;

    private EntityManagerInterface $em;

    public function __construct(ArrayCollection $fields)
    {
        $this->fields = $fields;
        $this->metaCollection = new ArrayCollection();
    }

    public function createView()
    {
        return $this ?? null;
    }

    /**
     * @return ArrayCollection
     */
    public function getFields(): ArrayCollection
    {
        return $this->fields;
    }

    public function handleData(array $data, $entity, EntityManagerInterface $em)
    {
        $this->data = new ArrayCollection($data);
        $this->entity = $entity;
        $this->em = $em;
        $this->attachData();
        return $this->metaCollection;
    }

    public function attachData()
    {
        $mapper = new FieldDataMapper();
        $mapper->attachDataToFieldItems($this->fields, $this->data);
        $this->updateMetaFields();
        return $this;
    }

    private function updateMetaFields(){
        foreach ($this->fields as $fieldItem) {
            /** @var FieldItem $fieldItem */
            $this->createMeta($fieldItem) ;
        }
    }

    private function findExistingMeta(FieldItem $item){
        $metas = $item->getField()->getMetas();
        return $metas->filter(function (Meta $meta){
            return $meta->getEntityId() == $this->entity->getId() && $meta->getEntityType() == $this->getOriginalClass(get_class($this->entity));
        })->first();
    }

    public function createMeta(FieldItem $item)
    {
        $meta = $this->findExistingMeta($item);
        $meta = $meta ?: new Meta();
        $meta->setField($item->getField());
        $meta->setEntityId($this->entity->getId());
        $meta->setEntityType($this->getOriginalClass(get_class($this->entity)));

        $data = $item->getData();
        $vars = $item->getVars();
//
//        if ($vars['isContainer']) {
//            $this->addContainerContent($meta, $data);
//        } elseif ($vars['isCollection']) {
//            $this->addCollectionContent($meta, $data);
//        } else {
//            $this->addSingleContent($meta, $data);
//        }

        $meta->setContent($data);

        $this->addChildrenMeta($item);
        $this->metaCollection->add($meta);

        return $this;
    }

    private function addContainerContent(Meta $meta, $data)
    {
        $content = json_encode($data);
        $meta->translate()->setContent($content);
    }

    private function addCollectionContent(Meta $meta, $data)
    {
        $sorted = [];
        foreach ($data as $row) {
            foreach ($row as $locale => $content) {
                $sorted[$locale][] = $content;
            }
        }
        foreach ($sorted as $locale => $collection) {
            $meta->translate($locale, false)->setContent($collection);
            $meta->mergeNewTranslations();
        }
    }

    private function addSingleContent(Meta $meta, $data)
    {
        foreach ($data as $locale => $content) {
            $meta->translate($locale, false)->setContent($content);
            $meta->mergeNewTranslations();
        }
    }

    private function addChildrenMeta(FieldItem $item)
    {
        foreach ($item->getChildren() as $child) {
            $this->createMeta($child);
        }
    }

    private function getOriginalClass($proxy){
        return $this->em->getClassMetadata($proxy)->name;
    }
}
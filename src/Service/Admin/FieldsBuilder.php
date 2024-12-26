<?php

namespace App\Service\Admin;

use App\Entity\Admin\Field;
use App\Entity\Admin\FieldsGroup;
use App\Entity\Admin\Meta;
use App\Field\FieldItem;
use App\Field\FieldsForm;
use App\Interface\FieldsBuilderInterface;
use Doctrine\Common\Collections\ArrayCollection;

class FieldsBuilder implements FieldsBuilderInterface
{
    private ArrayCollection $items;
    private $meta = [];

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    private function buildFieldsForm(FieldsGroup $group, $data = [])
    {
        $this->meta = $data;

        $fields = $group->getFields()->filter(function ($field) {
            return $field->getParent() == null;
        });

        foreach ($fields as $field) {
            $item = $this->setItem($field, $data);
            $this->items->add($item);
        }

        return $this->items;
    }

    private function setItem(Field $field, $data = []): FieldItem
    {
        $item = new FieldItem($field);
        $item->setData([]);
        $this->setMeta($item);
        $this->setChildren($item);
        return $item;
    }

    private function setMeta(FieldItem $item)
    {
        /** @var Meta $meta */
        foreach ($this->meta as $meta){
            if($meta->getField()->getId() == $item->getField()->getId()){
                $item->setMeta($meta);
            }
        }
    }

    private function setChildren(FieldItem $item): void
    {
        if ($item->getField()->getRelated()) {
            foreach ($item->getField()->getRelated() as $related) {
                $child = $this->setItem($related);
                $item->addChild($child);
            }
        }
    }

    public function create(FieldsGroup $group, $data = [])
    {
        return new FieldsForm($this->buildFieldsForm($group, $data));
    }
}
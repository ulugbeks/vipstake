<?php

namespace App\Service\Admin;

use App\Entity\Admin\Field;
use App\Entity\Admin\FieldsGroup;
use App\Entity\Admin\Meta;
use App\Field\FieldArray;
use App\Field\FieldItem;
use App\Interface\FieldsGroupInterface;
use App\Repository\Admin\FieldsGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\PersistentCollection;

class FieldsService
{
    public function getEntitiesWithFields(EntityManager $em)
    {
        $entities = $em->getMetadataFactory()->getAllMetadata();
        $supportedEntities = [];

        foreach ($entities as $entity) {

            if ($entity->getReflectionClass()->implementsInterface(FieldsGroupInterface::class)) {
                $supportedEntities[ucfirst($entity->table['name'])] = $entity->getName();
            }
        }

        return $supportedEntities;
    }

    public function getFieldsToDelete(FieldsGroup $group, PersistentCollection $collection)
    {
        $fields = [];

        $groupFields = $group->getFields()->map(function ($field) {
            return $field->getId();
        })->toArray();

        $newFields = $collection->map(function ($field) {
            return $field->getId();
        })->toArray();

        foreach ($groupFields as $field) {
            if (!in_array($field, $newFields)) {
                $toDelete = $group->getFields()->findFirst(function ($key, Field $toDelete) use ($field) {
                    return $toDelete->getId() === $field;
                });
                $group->removeField($toDelete);
            }
        }

        return true;
    }

    public function setParents(Field &$field, PersistentCollection $collection)
    {
        if ($field->getParentLabel()) {
            $parentLabel = $field->getParentLabel();
            $parent = $collection->findFirst(function ($key, Field $field) use ($parentLabel) {
                return $field->getLabel() == $parentLabel;
            });
            $field->setParent($parent);
        } else {
            $field->setParent(null);
        }
    }

    public function getMetaFields($entity, FieldsGroupRepository $repository, $class): ArrayCollection
    {
        $groups = new ArrayCollection();
        $reflectionClass = new \ReflectionClass($class);

        if ($reflectionClass->hasMethod('getTemplate')) {
            $template = $entity->getTemplate();
            $templateGroups = $repository->findBy(
                ['ruleType' => FieldsGroupInterface::RULE_TEMPLATE, 'ruleValue' => $template]
            );
            foreach ($templateGroups as $group) {
                $groups->add($group);
            }
        }

        $entityGroups = $repository->findBy(
            ['ruleType' => FieldsGroupInterface::RULE_ENTITY, 'ruleValue' => $reflectionClass->getName()]
        );

        foreach ($entityGroups as $group) {
            $groups->add($group);
        }

        return $groups;
    }

    public static function isFieldIterable(Field $field)
    {
        /** @var Field $parent */
        $parent = $field->getParent();
        if ($parent) {
            $instance = (new \ReflectionClass($parent->getType()))->newInstance();

            return $instance->isIterable();
        }

        return false;
    }

    public static function isContainer(Field $field)
    {
        /** @var Field $field */
        $instance = (new \ReflectionClass($field->getType()))->newInstance();

        return $instance->isContainer();
    }

    public static function isArray(Field $field)
    {
        /** @var Field $field */
        $reflection = new \ReflectionClass($field->getType());
        $method = 'deserialize';

        return $reflection->hasMethod($method);
    }

    /**
     * @param Meta[] $meta
     * @return array
     */
    public function transformData(array $meta): array
    {

        $fields = [];
        foreach ($meta as $field) {
            $fieldItem = new FieldItem($field->getField());

            if (!$fieldItem->getVar('isContainer')) {
                $map = explode('|', $fieldItem->getVar('array_map'));


                if ($fieldItem->getVar('isCollection')) {
                    $content = json_decode($field->getContent());
                    $data = [$field->getField()->getLabel() => $content];
                    $fieldArray = (new FieldArray())->createCollectionArray($map, $data);
                    $fields = array_replace_recursive($fieldArray, $fields);
                } elseif ($fieldItem->getVar('isArray')) {
                    $content = json_decode($field->getContent());
                    $data = [$field->getField()->getLabel() => $content];
                    $fields = array_replace_recursive($data, $fields);
                } else {
                    $content = $field->getContent();
                    $data = [$field->getField()->getLabel() => $content];
                    $fieldArray = (new FieldArray())->createArray($map, $data);
                    $fields = array_replace_recursive($fieldArray, $fields);
                }
            }
        }
        $this->removeEmptyArrays($fields);
        return $fields;
    }
    public function transformFieldData(array $meta): array
    {
        $fields = [];

        foreach ($meta as $field) {
            $fieldItem = new FieldItem($field->getField());
            if (!$fieldItem->getVar('isContainer')) {
                $map = explode('|', $fieldItem->getVar('array_map'));
                if ($fieldItem->getVar('isCollection')) {
                    $content = json_decode($field->getContent());
                    $data = [$field->getField()->getLabel() => $content];
                    $fieldArray = (new FieldArray())->createCollectionArray($map, $data);
                    $fields = array_replace_recursive($fieldArray, $fields);
                } elseif ($fieldItem->getVar('isArray')) {
                    $content = json_decode($field->getContent());
                    $data = [$field->getField()->getLabel() => $content];
                    $fields = array_replace_recursive($data, $fields);
                } else {
                    $content = $field->getContent();
                    $data = [$field->getField()->getLabel() => $content];
                    $fieldArray = (new FieldArray())->createArray($map, $data);
                    $fields = array_replace_recursive($fieldArray, $fields);
                }
            }
        }

        return $fields;
    }

    private function removeEmptyArrays(&$array) {
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                $this->removeEmptyArrays($value); // Recurse into the sub-array
                if (empty($value)) {
                    unset($array[$key]); // Unset if the sub-array is empty
                }
            } else if ($value === "") {
                unset($array[$key]); // Unset if the value is empty
            }
        }
    }
}
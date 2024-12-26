<?php

namespace App\Field;

use App\Entity\Admin\Field;
use App\Entity\Admin\Meta;
use App\Interface\FieldsBuilderInterface;
use App\Service\Admin\FieldsService;
use Doctrine\Common\Collections\ArrayCollection;

class FieldItem
{
    private Field $field;
    private array $vars;
    private ArrayCollection $children;
    private ?Meta $meta;

    public function __construct(Field $field)
    {
        $this->field = $field;
        $this->children = new ArrayCollection();
        $this->setVars();
        $this->meta = null;
    }

    /**
     * @return Field
     */
    public function getField(): Field
    {
        return $this->field;
    }


    /**
     * @return array
     */
    public function getData(): mixed
    {
        return $this->vars['data'];
    }

    /**
     * @param string|array $data
     */
    public function setData(mixed $data): void
    {
        $this->vars['data'] = empty($data) ? '' : $data;
    }

    private function setVars(){
        $this->setId();
        $this->setName();
        $this->vars['label'] = $this->getField()->getName();
        $this->vars['isCollection'] = (bool)FieldsService::isFieldIterable($this->getField());
        $this->vars['isContainer'] = (bool)FieldsService::isContainer($this->getField());
        $this->vars['array_map'] = $this->setArrayMap();
        $this->vars['parent'] = $this->getParentLabel();
        $this->vars['isArray'] = FieldsService::isArray($this->getField());
    }

    /**
     * @return array
     */
    public function getVars(): array
    {
        return $this->vars;
    }

    /**
     * @return ArrayCollection
     */
    public function getChildren(): ArrayCollection
    {
        return $this->children;
    }

    public function addChild(FieldItem $child): FieldItem
    {
        $this->children->add($child);
        return $this;
    }

    public function setId()
    {
        $this->vars['id'] = FieldsBuilderInterface::BASE_PATH . '_' . $this->getField()->getLabel();

        if (FieldsService::isFieldIterable($this->getField())) {
            $this->addIdPostfix('__index__');
        }
    }

    public function setName()
    {
        $index = '';
        if (FieldsService::isFieldIterable($this->getField())) {
           $index = '[__index__]';
        }

        $this->vars['full_name'] = FieldsBuilderInterface::BASE_PATH . $this->getParentPath() . $index . '[' . $this->getField()->getLabel() . ']';
    }

    public function getParentLabel() : ?string
    {
        return $this->getField()->getParent()?->getLabel();
    }

    public function setArrayMap(){
      return $this->getParrentArrayMap() . $this->getField()->getLabel();
    }

    public function addIdPostfix($postfix)
    {
        $this->vars['id'] .= $postfix;
    }

    public function addNamePostfix($postfix)
    {
        $this->vars['full_name'] .= $postfix;
    }

    public function getParrentArrayMap(){
        $map = '';
        $labels = $this->getAllParentLabels();

        foreach ($labels as $label) {
            $map .= $label . "|";
        }

        return $map;
    }

    public function getParentPath(): string
    {
        $path = '';
        $labels = $this->getAllParentLabels();
        foreach ($labels as $label) {
            $path .= "[" . $label . "]";
        }

        return $path;
    }

    public function getAllParentLabels()
    {
        $field = $this->getField();
        $labels = [];
        while ($field->getParent()) {
            array_unshift($labels, $field->getParent()->getLabel());
            $field = $field->getParent();
        }
        return $labels;
    }

    public function getVar($var){
        return $this->vars[$var] ?? null;
    }

    /**
     * @return null|Meta
     */
    public function getMeta(): ?Meta
    {
        return $this->meta;
    }

    /**
     * @param Meta $meta
     */
    public function setMeta(Meta $meta): void
    {
        $this->meta = $meta;
    }

    public function getValue(){
        if(!empty($this->meta)){
            $value = $this->meta->getContent();
            return $this->vars['isCollection'] || $this->vars['isContainer'] ? json_decode($value, true) : $value;
        }

        return null;
    }
}
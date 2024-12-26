<?php

namespace App\PageBuilder\Block;

class AbstractBlock
{
    public function toArray(): array
    {
        $block = [
            'id' => $this->getId(),
            'label' => $this->getLabel(),
        ];

        foreach ($this->getFields() as $field) {
            $block['fields'][$field->getName()] = $field->toArray();
        }

        return $block;
    }
}
<?php

namespace App\Field;

use Doctrine\Common\Collections\ArrayCollection;

class FieldDataMapper
{
    /**
     * Attaches data to the FieldItems in the given ArrayCollection.
     */
    public function attachDataToFieldItems(ArrayCollection $fieldItems, ArrayCollection $data): void
    {
        foreach ($fieldItems as $fieldItem) {
            $fieldLabel = $fieldItem->getField()->getLabel();
            $fieldData = $data->get($fieldLabel);

            if ($fieldData) {
                $this->attachDataToFieldItem($fieldItem, $fieldData);
            }
        }

    }

    /**
     * Recursively attaches data to the given FieldItem and its children.
     */
    private function attachDataToFieldItem(FieldItem $fieldItem, $data): void
    {
        $fieldItem->setData($data);

        foreach ($fieldItem->getChildren() as $childFieldItem) {
            $childFieldLabel = $childFieldItem->getField()->getLabel();
            $childFieldData = $data[$childFieldLabel] ?? null;

            if ($childFieldData) {
                $this->attachDataToFieldItem($childFieldItem, $childFieldData);
            }
        }
    }
}

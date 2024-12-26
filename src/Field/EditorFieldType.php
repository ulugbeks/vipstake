<?php

namespace App\Field;

use App\Interface\FieldInterface;

class EditorFieldType implements FieldInterface
{

    public function getType(): string
    {
        return 'editor';
    }

    public function isContainer(): bool
    {
        return false;
    }

    public function isIterable(): bool
    {
        return false;
    }
}
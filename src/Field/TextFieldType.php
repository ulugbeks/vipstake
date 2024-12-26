<?php

namespace App\Field;

use App\Interface\FieldInterface;

class TextFieldType implements FieldInterface
{
    public function getType(): string
    {
       return 'text';
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
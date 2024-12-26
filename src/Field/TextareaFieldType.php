<?php

namespace App\Field;

use App\Interface\FieldInterface;

class TextareaFieldType implements FieldInterface
{

    public function getType(): string
    {
        return 'textarea';
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
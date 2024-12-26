<?php

namespace App\Field;

use App\Interface\FieldInterface;

class ImageFieldType implements FieldInterface
{
    public function getType(): string
    {
       return 'image';
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
<?php

namespace App\Field;

use App\Interface\FieldInterface;

class GroupFieldType implements FieldInterface
{
    public function getType(): string
    {
       return 'group';
    }

    public function isContainer(): bool
    {
        return true;
    }

    public function isIterable(): bool
    {
        return false;
    }
}
<?php

namespace App\Field;

use App\Interface\FieldInterface;

class RepeaterFieldType implements FieldInterface
{
    public function getType(): string
    {
       return 'repeater';
    }

    public function isContainer(): bool
    {
        return true;
    }

    public function isIterable(): bool
    {
        return true;
    }
}
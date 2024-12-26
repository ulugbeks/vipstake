<?php

namespace App\Field;

use App\Interface\FieldInterface;

class GalleryFieldType implements FieldInterface
{

    public function getType(): string
    {
        return 'gallery';
    }

    public function isContainer(): bool
    {
        return false;
    }

    public function isIterable(): bool
    {
        return true;
    }

    public function deserialize($value){
        return json_decode($value, true);
    }
}
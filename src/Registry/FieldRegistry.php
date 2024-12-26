<?php

namespace App\Registry;

use App\Field\EditorFieldType;
use App\Field\GalleryFieldType;
use App\Field\GroupFieldType;
use App\Field\ImageFieldType;
use App\Field\RepeaterFieldType;
use App\Field\TextareaFieldType;
use App\Field\TextFieldType;

class FieldRegistry
{
    public static function getFields()
    {
        return [
            TextFieldType::class,
            TextareaFieldType::class,
            GroupFieldType::class,
            RepeaterFieldType::class,
            EditorFieldType::class,
            GalleryFieldType::class,
            ImageFieldType::class
        ];
    }
}
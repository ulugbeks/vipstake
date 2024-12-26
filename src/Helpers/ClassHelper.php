<?php

namespace App\Helpers;

class ClassHelper
{
    public static function getShortClassName($classNameWithNamespace)
    {
        return substr($classNameWithNamespace, strrpos($classNameWithNamespace, '\\') + 1);
    }

    public static function getOriginalClass($result)
    {
        $realClass = get_class($result);

        if ($realClass === 'Proxies\__CG__\App\Entity\Frontend\Country') {
            $realClass = get_parent_class($result);
        }

// Now you have the real class name, and you can create a new instance of it
        $realEntity = new $realClass();

// You can then copy the properties from the proxy object to the real entity if needed
        $properties = get_object_vars($result);
        foreach ($properties as $property => $value) {
            // Assuming the properties are public, otherwise, you might need to use reflection to set the values.
            $realEntity->$property = $value;
        }

        return $realEntity;
    }

    public static function escapeClassName(string $classname): string
    {
        return str_replace('\\', '\\\\', $classname);
    }
}
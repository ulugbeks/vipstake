<?php

namespace App\Field;

class FieldArray
{
    private $array;

    public function __construct()
    {
        $this->array = [];
    }

    public function getArray()
    {
        return $this->array;
    }

    public function createArray($keys, $data)
    {
        $multiDimensionalArray = [];
        $currentArray = &$multiDimensionalArray;
        foreach ($keys as $key) {
            // Проверяем, есть ли ключ в массиве данных
            if (isset($data[$key])) {
                // Если есть, присваиваем значение из $data
                $currentArray[$key] = $data[$key];
            }
            // Переходим к добавленному дочернему массиву
            $currentArray = &$currentArray[$key];
        }

        return $multiDimensionalArray;
    }

    public function createCollectionArray($keyMap, $values)
    {
        $arr = [];
        $currentArray = &$arr;


        foreach ($keyMap as $key) {
            if (isset($values[$key])) {
                foreach ($values[$key] as $value) {
                    $currentArray[][$key] = $value;
                }
            } else {
                $currentArray[$key] = [];
                $currentArray = &$currentArray[$key];
            }
        }

        return $arr;
    }
}

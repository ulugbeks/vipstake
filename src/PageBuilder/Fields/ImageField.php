<?php

namespace App\PageBuilder\Fields;

use App\PageBuilder\Interface\FieldInterface;

class ImageField implements FieldInterface
{
    private string $name;
    private mixed $default;
    private mixed $id;

    public function __construct(string $name, $default = null)
    {
        $this->name = $name;
        $this->default = $default;
        $this->id = uniqid('field_', true);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDefault(): mixed
    {
        return $this->default;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'default' => $this->getDefault()
        ];
    }
}
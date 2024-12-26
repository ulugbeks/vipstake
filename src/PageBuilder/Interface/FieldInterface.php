<?php

namespace App\PageBuilder\Interface;

interface FieldInterface
{
    public function __construct(string $name, $default = null);

    public function getName(): string;

    public function getDefault(): mixed;

    public function getId(): string;

    public function toArray(): array;
}
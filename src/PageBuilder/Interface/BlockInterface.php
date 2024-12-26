<?php

namespace App\PageBuilder\Interface;

interface BlockInterface
{
    public const AREA_TEXT = 'area_text';

    public function getName(): string;

    public function getLabel(): string;

    public function getFields(): array;

    public function getAssets(array $assets): array;

    public function getTemplate(): string;

    public function getArea(): string;

    public function getId(): string;

    public function toArray(): array;
}
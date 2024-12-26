<?php

namespace App\PageBuilder\Interface;

interface LayoutInterface
{
    public function getName(): string;

    public function getLabel(): string;

    public function getTemplate(): string;

    public function getId(): string;

    public function toArray(): array;
}
<?php

namespace App\Interface;

interface FieldInterface
{
    public function getType(): string;

    /**
     * @return bool
     *
     * Checks is field container for fields (Repeater, Group)
     */
    public function isContainer(): bool;
    public function isIterable():bool;
}
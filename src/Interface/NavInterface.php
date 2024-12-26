<?php

namespace App\Interface;

interface NavInterface
{
    /** Method that require to return one of entity attributes for ChoicesType output */
    public function getNavLabel(): string;
}
<?php

namespace App\Interface;

interface StatusInterface
{
    const PUBLISH = 'publish';
    const PRIVATE = 'private';
    const DRAFT = 'draft';
    public function setStatus(string $status): self;
    public function getStatus(): ?string;
}
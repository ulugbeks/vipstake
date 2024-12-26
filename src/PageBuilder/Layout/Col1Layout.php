<?php

namespace App\PageBuilder\Layout;

use App\PageBuilder\Interface\LayoutInterface;

class Col1Layout implements LayoutInterface
{
    private string $id;

    public function __construct()
    {
        $this->id = uniqid('container_', true);
    }

    public function getName(): string
    {
        return 'one_column';
    }

    public function getLabel(): string
    {
        return 'One Column';
    }

    public function getTemplate(): string
    {
        return '/layout/col-1.html.twig';
    }

    public function getId(): string
    {
       return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function toArray(): array
    {
       return [
           'id' => $this->getId(),
           'name' => $this->getName(),
           'label' => $this->getLabel()
       ];
    }
}
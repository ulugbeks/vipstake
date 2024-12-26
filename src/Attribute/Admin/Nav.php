<?php

namespace App\Attribute\Admin;

#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class Nav
{
    private string $icon;
    private string $name;
    private string $order;
    private array $roles;
    private ?string $route;

    public function __construct(string $icon, string $name, string $order, array $roles = [], string $route = null)
    {
        $this->icon = $icon;
        $this->name = $name;
        $this->order = $order;
        $this->roles = $roles;
        $this->route = $route;
    }

    public function getNavItem(){
        return [
            $this->icon, $this->name, $this->order, $this->roles, $this->route
        ];
    }
}
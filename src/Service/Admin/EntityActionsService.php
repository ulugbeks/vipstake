<?php

namespace App\Service\Admin;

use App\Attribute\Admin\Editable;
use App\Controller\Admin\AdminController;
use App\Trait\Admin\ClassManagerTrait;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\Routing\Attribute\Route;

class EntityActionsService
{
    use ClassManagerTrait;

    private object $entity;

    public function getUrl(object $entity, string $action, string $originalClassName): ?string
    {
        $adminControllers = $this->getSubclasses(AdminController::class);
        $this->entity = $entity;

        foreach ($adminControllers as $class) {

            $method = $this->getEditableMethod($class, $originalClassName, $action);

            if ($method !== null) {
                $uri = $this->getRouteUri($method);
                $controllerReflection = new ReflectionClass($class);
                $routeAttribute = $controllerReflection->getAttributes(Route::class);

                if(!empty($routeAttribute)){
                    $uri = $routeAttribute[0]->getArguments()[0] . $uri;
                }

                return $uri;
            }
        }

        return null;
    }

    private function getEditableMethod(string $class, string $entity, string $action): ?ReflectionMethod
    {
        $reflection = new ReflectionClass($class);

        foreach ($reflection->getMethods() as $method) {
            $editable = $method->getAttributes(Editable::class)[0] ?? null;

            if ($editable !== null && $editable->newInstance()->getEntity() === $entity && $editable->newInstance(
                )->getAction() === $action) {
                return $method;
            }
        }

        return null;
    }

    private function getRouteUri(ReflectionMethod $method): string
    {
        $path = $method->getAttributes(Route::class)[0]->newInstance()->getPath();

        return str_replace('{id}', $this->entity->getId(), $path);
    }
}

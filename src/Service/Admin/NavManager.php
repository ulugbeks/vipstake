<?php

namespace App\Service\Admin;

use App\Attribute\Admin\Nav;
use App\Controller\Admin\AdminController;
use App\Trait\Admin\ClassManagerTrait;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class NavManager
{
    use ClassManagerTrait;

    private $user;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->user = $tokenStorage->getToken()->getUser();
    }

    /**
     * @throws ReflectionException
     */
    public function getNav()
    {
        $nav = [];
        $childClasses = [];
        $classes = $this->getClasses();

        foreach ($classes as $class) {
            if (is_subclass_of($class, AdminController::class)) {
                $childClasses[] = $class;
            }
        }

        foreach ($childClasses as $class) {
            $reflection = new ReflectionClass($class);

            $methods = $reflection->getMethods();
            foreach ($methods as $method) {
                $routeAttribute = $method->getAttributes(Route::class);
                if ($routeAttribute) {
                    foreach ($method->getAttributes() as $attribute) {
                        if ($attribute->getName() == Nav::class) {
                            $arguments = $attribute->getArguments();

                            if(!isset($arguments['route'])){
                                $arguments['route'] = $routeAttribute[0]->getArguments()[0];
                            }

                            if($this->isGranted($arguments)){
                                $nav[] = $arguments;
                            }
                        }
                    }
                }
            }
        }

        usort($nav, function ($a, $b) {
            return $a['order'] <=> $b['order'];
        });

        return $nav;
    }

    private function isGranted($args)
    {
        if (!isset($args['roles']) || empty($args['roles'])) {
            return true;
        }

        foreach ($this->user->getRoles() as $role) {
            if (in_array($role, $args['roles'])) {
                return true;
            }
        }

        return false;
    }
}
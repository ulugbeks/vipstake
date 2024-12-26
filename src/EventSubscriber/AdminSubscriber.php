<?php

namespace App\EventSubscriber;

use App\Controller\Admin\AdminController;
use App\Service\Admin\SettingsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

readonly class AdminSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private SettingsService $settingsService,
    ) {
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();
        if (is_array($controller) && is_object($controller[0])) {
            $isAdminController = is_subclass_of($controller[0], AdminController::class);

            if ($isAdminController) {
                $this->settingsService->init();
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
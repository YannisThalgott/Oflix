<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class MaintenanceSubscriber implements EventSubscriberInterface
{
    private  $isMaintenance;

    public function __construct($isMaintenance)
    {
        $this->isMaintenance = $isMaintenance;
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        if (!$event->isMainRequest()) {
            return;
        }

        if (preg_match('/^\/(_profiler|_wdt)/', $event->getRequest()->getPathInfo())) {
            return;
        }

        if ($event->getRequest()->isXmlHttpRequest()) {
            return;
        }

        if ($this->isMaintenance) {
        $response = $event->getResponse();
        $content = $response->getContent();

        $content = str_replace(
            '</nav>',
            '</nav><div class="container alert alert-danger mt-3">Maintenance prévue mardi 10 janvier à 17h00</div>', $content);

            $event->getResponse()->setContent($content);
        };
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.response' => 'onKernelResponse',
        ];
    }
}

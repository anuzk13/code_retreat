<?php

namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class JsonRequestSubscriber implements EventSubscriberInterface
{

    public function __construct()
    {
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $request = $event->getRequest();
        if ($request->getContentType() === 'json') {
            $content = $request->getContent();
            $params = json_decode($content, true);
            $event->getRequest()->attributes->set('json_body', $params);
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => 'onKernelController',
        );
    }
}
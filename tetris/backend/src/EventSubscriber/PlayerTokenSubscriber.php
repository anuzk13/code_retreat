<?php

namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use App\Service\PlayerService;

class PlayerTokenSubscriber implements EventSubscriberInterface
{

    private $playerService;
    private $logger;

    public function __construct(PlayerService $playerService, LoggerInterface $logger)
    {
        $this->playerService = $playerService;
        $this->logger = $logger;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $token = $event->getRequest()->headers->get('Authorization');
        if ($token) {
            $this->log['Auth header'] = $token;
            $this->logger->info(json_encode($this->log));
            $player = $this->playerService->playerFromToken($token);
            $event->getRequest()->attributes->set('_player', $player);
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => 'onKernelController',
        );
    }
}
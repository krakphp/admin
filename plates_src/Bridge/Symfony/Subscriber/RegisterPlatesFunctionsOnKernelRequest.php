<?php

namespace League\Plates\Bridge\Symfony\Subscriber;

// TODO: only register these functions for the paths that will actually use plates
use League\Plates\Bridge\Symfony\RoutingFunctions;
use League\Plates\Bridge\Symfony\SessionFunctions;
use League\Plates\ScopedRegistry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class RegisterPlatesFunctionsOnKernelRequest implements EventSubscriberInterface
{
    private $routingFunctions;
    private $registry;
    private $sessionFunctions;

    public function __construct(ScopedRegistry $registry, RoutingFunctions $routingFunctions, SessionFunctions $sessionFunctions) {
        $this->registry = $registry;
        $this->routingFunctions = $routingFunctions;
        $this->sessionFunctions = $sessionFunctions;
    }

    public function __invoke(RequestEvent $event) {
        $this->registry->set(RoutingFunctions::class, $this->routingFunctions);
        $this->registry->set(SessionFunctions::class, $this->sessionFunctions);
    }

    public static function getSubscribedEvents() {
        return [KernelEvents::REQUEST => '__invoke'];
    }
}

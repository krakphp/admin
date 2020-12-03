<?php

namespace League\Plates\Bridge\Symfony\Subscriber;

// TODO: only register these functions for the paths that will actually use plates
use League\Plates\Bridge\Symfony\RoutingFunctions;
use League\Plates\ScopedRegistry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class RegisterPlatesFunctionsOnKernelRequest implements EventSubscriberInterface
{
    private $routingFunctions;
    private $registry;

    public function __construct(ScopedRegistry $registry, RoutingFunctions $routingFunctions) {
        $this->registry = $registry;
        $this->routingFunctions = $routingFunctions;
    }

    public function __invoke(RequestEvent $event) {
        $this->registry->set(RoutingFunctions::class, $this->routingFunctions);
    }

    public static function getSubscribedEvents() {
        return [KernelEvents::REQUEST => '__invoke'];
    }
}

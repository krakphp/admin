<?php

namespace League\Plates\Bridge\Symfony\Subscriber;

use League\Plates\ComponentContext;
use League\Plates\ProvideComponentContext;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class ProvideComponentContextOnKernelRequest implements EventSubscriberInterface
{
    private $context;
    private $contextProviders;

    /** @param ProvideComponentContext[] $contextProviders */
    public function __construct(ComponentContext $context, iterable $contextProviders) {
        $this->context = $context;
        $this->contextProviders = $contextProviders;
    }

    public function __invoke(RequestEvent $event) {
        foreach ($this->contextProviders as $provide) {
            $provide($this->context);
        }
    }

    public static function getSubscribedEvents() {
        return [KernelEvents::REQUEST => '__invoke'];
    }
}

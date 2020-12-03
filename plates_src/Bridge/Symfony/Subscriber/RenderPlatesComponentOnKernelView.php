<?php

namespace League\Plates\Bridge\Symfony\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use function League\Plates\p;

final class RenderPlatesComponentOnKernelView implements EventSubscriberInterface
{
    public function __invoke(ViewEvent $event) {
        if (is_callable($event->getControllerResult())) {
            $event->setResponse(new Response(p($event->getControllerResult())));
        }
    }

    public static function getSubscribedEvents() {
        return [KernelEvents::VIEW => '__invoke'];
    }
}

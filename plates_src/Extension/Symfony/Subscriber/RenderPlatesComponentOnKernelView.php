<?php

namespace League\Plates\Extension\Symfony\Subscriber;

use League\Plates\Component;
use League\Plates\ComponentContext;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class RenderPlatesComponentOnKernelView implements EventSubscriberInterface
{
    private $context;

    public function __construct(ComponentContext $context) {
        $this->context = $context;
    }

    public function __invoke(ViewEvent $event) {
        if ($event->getControllerResult() instanceof Component || is_callable($event->getControllerResult())) {
            $event->setResponse(new Response(
                $this->context->render($event->getControllerResult())
            ));
        }
    }

    public static function getSubscribedEvents() {
        return [KernelEvents::VIEW => '__invoke'];
    }
}

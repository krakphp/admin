<?php

namespace Krak\Admin\Bridge\Symfony\EventSubscriber;

use Krak\Admin\Form\ConvertFieldToHtmlElement;
use League\Plates\ScopedRegistry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class InitPlatesRegistryOnKernelRequest implements EventSubscriberInterface
{
    private $registry;
    private $convertFieldToHtmlElement;

    public function __construct(ScopedRegistry $registry, ConvertFieldToHtmlElement $convertFieldToHtmlElement) {
        $this->registry = $registry;
        $this->convertFieldToHtmlElement = $convertFieldToHtmlElement;
    }

    public function __invoke(RequestEvent $event) {
        $this->registry->set(ConvertFieldToHtmlElement::class, $this->convertFieldToHtmlElement);
    }

    public static function getSubscribedEvents() {
        return [KernelEvents::REQUEST => '__invoke'];
    }
}

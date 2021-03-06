<?php

namespace League\Plates\Extension\Symfony\UI\Provider;

use League\Plates\Extension\Symfony\CsrfFunctions;
use League\Plates\Extension\Symfony\RoutingFunctions;
use League\Plates\Extension\Symfony\SessionFunctions;
use League\Plates\ComponentContext;
use League\Plates\ProvideComponentContext;

final class ProvidePlatesFunctions implements ProvideComponentContext
{
    private $routingFunctions;
    private $sessionFunctions;
    private $csrfFunctions;

    public function __construct(RoutingFunctions $routingFunctions, SessionFunctions $sessionFunctions, CsrfFunctions $csrfFunctions) {
        $this->routingFunctions = $routingFunctions;
        $this->sessionFunctions = $sessionFunctions;
        $this->csrfFunctions = $csrfFunctions;
    }

    public function __invoke(ComponentContext $context): void {
        $context->addStatic(RoutingFunctions::class, $this->routingFunctions);
        $context->addStatic(SessionFunctions::class, $this->sessionFunctions);
        $context->addStatic(CsrfFunctions::class, $this->csrfFunctions);
    }
}

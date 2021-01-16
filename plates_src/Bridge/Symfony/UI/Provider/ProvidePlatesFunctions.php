<?php

namespace League\Plates\Bridge\Symfony\UI\Provider;

use League\Plates\Bridge\Symfony\RoutingFunctions;
use League\Plates\Bridge\Symfony\SessionFunctions;
use League\Plates\ComponentContext;
use League\Plates\ProvideComponentContext;

final class ProvidePlatesFunctions implements ProvideComponentContext
{
    private $routingFunctions;
    private $sessionFunctions;

    public function __construct(RoutingFunctions $routingFunctions, SessionFunctions $sessionFunctions) {
        $this->routingFunctions = $routingFunctions;
        $this->sessionFunctions = $sessionFunctions;
    }

    public function __invoke(ComponentContext $context): void {
        $context->addStatic(RoutingFunctions::class, $this->routingFunctions);
        $context->addStatic(SessionFunctions::class, $this->sessionFunctions);
    }
}

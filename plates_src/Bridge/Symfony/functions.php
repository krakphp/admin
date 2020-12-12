<?php

namespace League\Plates\Bridge\Symfony;

use League\Plates\ScopedRegistry;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;

function url(string $name, array $parameters = [], bool $relative = false) {
    return ScopedRegistry::self()->get(RoutingFunctions::class)->url($name, $parameters, $relative);
}

function path(string $name, array $parameters = [], bool $relative = false) {
    return ScopedRegistry::self()->get(RoutingFunctions::class)->path($name, $parameters, $relative);
}

function flashes(): ?FlashBagInterface {
    return ScopedRegistry::self()->get(SessionFunctions::class)->flashes();
}

function session(): ?Session {
    return ScopedRegistry::self()->get(SessionFunctions::class)->session();
}

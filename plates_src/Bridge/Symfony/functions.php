<?php

namespace League\Plates\Bridge\Symfony;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use function League\Plates\context;

function url(string $name, array $parameters = [], bool $relative = false) {
    return context(RoutingFunctions::class)->url($name, $parameters, $relative);
}

function path(string $name, array $parameters = [], bool $relative = false) {
    return context(RoutingFunctions::class)->path($name, $parameters, $relative);
}

function flashes(): ?FlashBagInterface {
    return context(SessionFunctions::class)->flashes();
}

function session(): ?Session {
    return context(SessionFunctions::class)->session();
}

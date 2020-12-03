<?php

namespace League\Plates\Bridge\Symfony;

use League\Plates\ScopedRegistry;

function url(string $name, array $parameters = [], bool $relative = false) {
    return ScopedRegistry::self()->get(RoutingFunctions::class)->url($name, $parameters, $relative);
}

function path(string $name, array $parameters = [], bool $relative = false) {
    return ScopedRegistry::self()->get(RoutingFunctions::class)->path($name, $parameters, $relative);
}

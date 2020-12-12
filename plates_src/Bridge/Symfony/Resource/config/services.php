<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use League\Plates\Bridge\Symfony\RoutingFunctions;
use League\Plates\Bridge\Symfony\SessionFunctions;
use League\Plates\Bridge\Symfony\Subscriber\RegisterPlatesFunctionsOnKernelRequest;
use League\Plates\Bridge\Symfony\Subscriber\RenderPlatesComponentOnKernelView;
use League\Plates\ScopedRegistry;

return static function (ContainerConfigurator $configurator) {
    $configurator
    ->services()
        ->defaults()
            ->private()->autoconfigure()->autowire()
        ->set(RenderPlatesComponentOnKernelView::class)
        ->set(RegisterPlatesFunctionsOnKernelRequest::class)
        ->set(RoutingFunctions::class)
        ->set(SessionFunctions::class)
        ->set(ScopedRegistry::class)
            ->factory(ScopedRegistry::class . '::self')
    ;
};

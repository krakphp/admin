<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use League\Plates\Bridge\Symfony\RoutingFunctions;
use League\Plates\Bridge\Symfony\SessionFunctions;
use League\Plates\Bridge\Symfony\Subscriber\ProvideComponentContextOnKernelRequest;
use League\Plates\Bridge\Symfony\Subscriber\RenderPlatesComponentOnKernelView;
use League\Plates\Bridge\Symfony\UI\Provider\ProvidePlatesFunctions;
use League\Plates\ComponentContext;

return static function (ContainerConfigurator $configurator) {
    $configurator
    ->services()
        ->defaults()
            ->private()->autoconfigure()->autowire()
        ->set(RenderPlatesComponentOnKernelView::class)
        ->set(ProvidePlatesFunctions::class)
        ->set(RoutingFunctions::class)
        ->set(SessionFunctions::class)
        ->set(ComponentContext::class)
        ->set(ProvideComponentContextOnKernelRequest::class)
            ->arg('$contextProviders', tagged_iterator('plates.provide_component_context'))
    ;
};

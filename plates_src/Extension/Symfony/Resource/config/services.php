<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use League\Plates\Extension\Symfony\CsrfFunctions;
use League\Plates\Extension\Symfony\RoutingFunctions;
use League\Plates\Extension\Symfony\SessionFunctions;
use League\Plates\Extension\Symfony\Subscriber\ProvideComponentContextOnKernelRequest;
use League\Plates\Extension\Symfony\Subscriber\RenderPlatesComponentOnKernelView;
use League\Plates\Extension\Symfony\UI\Provider\ProvidePlatesFunctions;
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
        ->set(CsrfFunctions::class)
        ->set(ComponentContext::class)
        ->set(ProvideComponentContextOnKernelRequest::class)
            ->arg('$contextProviders', tagged_iterator('plates.provide_component_context'))
    ;
};

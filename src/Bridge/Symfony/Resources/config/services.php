<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Krak\Admin\Bridge\Symfony\EventSubscriber\InitPlatesRegistryOnKernelRequest;
use Krak\Admin\Form\ConvertFieldToHtmlElement;

return static function(ContainerConfigurator $configurator) {
    $configurator
    ->services()
        ->defaults()
            ->private()
            ->autowire()
            ->autoconfigure()
        ->set(ConvertFieldToHtmlElement::class, ConvertFieldToHtmlElement\StaticMappingConvertFieldToHtmlElement::class)
        ->set(InitPlatesRegistryOnKernelRequest::class)
    ;
};

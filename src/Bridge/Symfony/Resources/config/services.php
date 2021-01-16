<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Krak\Admin\Form\ConvertFieldToHtmlElement;
use Krak\Admin\Provider\ProvideAdminContext;

return static function(ContainerConfigurator $configurator) {
    $configurator
    ->services()
        ->defaults()
            ->private()
            ->autowire()
            ->autoconfigure()
        ->set(ConvertFieldToHtmlElement::class, ConvertFieldToHtmlElement\StaticMappingConvertFieldToHtmlElement::class)
        ->set(ProvideAdminContext::class)
    ;
};

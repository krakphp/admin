<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Demo\App\Catalog\App\HandleCreateSizeScale;
use Demo\App\Catalog\Domain\SizeScaleRepository;
use Demo\App\Catalog\Infra\Persistence\DoctrineSizeScaleRepository;
use Demo\App\Catalog\UI\Http\SizeScaleAdminController;

return static function(ContainerConfigurator $c) {
    $c->services()
        ->defaults()->autoconfigure()->autowire()->private()
    ->set(SizeScaleRepository::class, DoctrineSizeScaleRepository::class)
    ->set(SizeScaleAdminController::class)
    ->set(HandleCreateSizeScale::class)
    ;
};

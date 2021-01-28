<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Demo\App\Catalog\App\HandleCreateSizeScale;
use Demo\App\Catalog\App\HandleDeleteSizeScale;
use Demo\App\Catalog\App\HandlePublishSizeScale;
use Demo\App\Catalog\App\HandleUpdateSizeScale;
use Demo\App\Catalog\Domain\SizeScaleRepository;
use Demo\App\Catalog\Infra\Persistence\DoctrineSizeScaleRepository;
use Demo\App\Catalog\UI\Http\SizeScaleAdmin\SizeScalePublishAction;
use Demo\App\Catalog\UI\Http\SizeScaleAdminController;
use Demo\App\Catalog\UI\Http\ValidateCsrfEventSubscriber;

return static function(ContainerConfigurator $c) {
    $c->services()
        ->defaults()->autoconfigure()->autowire()->private()
    ->set(SizeScaleRepository::class, DoctrineSizeScaleRepository::class)
    ->set(SizeScaleAdminController::class)
    ->set(SizeScalePublishAction::class)
    ->set(HandleCreateSizeScale::class)
    ->set(HandleDeleteSizeScale::class)
    ->set(HandleUpdateSizeScale::class)
    ->set(HandlePublishSizeScale::class)
    ->set(ValidateCsrfEventSubscriber::class)
    ;
};

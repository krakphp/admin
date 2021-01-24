<?php

namespace Symfony\Component\Routing\Loader\Configurator;

use Demo\App\Catalog\UI\Http\SizeScaleAdminController;

return static function(RoutingConfigurator $routes) {
    $routes
        ->add('catalog_size_scale_admin_list', '/size-scales')
            ->controller([SizeScaleAdminController::class, 'listAction'])
        ->add('catalog_size_scale_admin_view', '/size-scales/{id}')
            ->controller([SizeScaleAdminController::class, 'viewAction'])
    ;
};

<?php

namespace Symfony\Component\Routing\Loader\Configurator;

use Demo\App\Catalog\UI\Http\SizeScaleAdminController;

return static function(RoutingConfigurator $routes) {
    $routes
        ->add('catalog_size_scale_admin_list', '/size-scales')
            ->controller([SizeScaleAdminController::class, 'listAction'])
            ->methods(['GET'])
        ->add('catalog_size_scale_admin_create', '/size-scales/create')
            ->controller([SizeScaleAdminController::class, 'createAction'])
            ->methods(['GET', 'POST'])
       ->add('catalog_size_scale_admin_view', '/size-scales/{id}')
            ->controller([SizeScaleAdminController::class, 'viewAction'])
            ->methods(['GET'])
        ->add('catalog_size_scale_admin_delete', '/size-scales/{id}/delete')
            ->controller([SizeScaleAdminController::class, 'deleteAction'])
            ->methods(['POST'])
    ;
};

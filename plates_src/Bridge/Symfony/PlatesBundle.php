<?php

namespace League\Plates\Bridge\Symfony;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class PlatesBundle extends Bundle
{
    public function getContainerExtension() {
        return new class() extends Extension {
            public function getAlias() {
                return 'plates';
            }

            public function load(array $configs, ContainerBuilder $container) {
                (new PhpFileLoader($container, new FileLocator(__DIR__ . '/Resource/config')))->load('services.php');
            }
        };
    }
}


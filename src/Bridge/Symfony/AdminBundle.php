<?php

namespace Krak\Admin\Bridge\Symfony;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class AdminBundle extends Bundle
{
    public function getContainerExtension() {
        return new class() extends Extension {
            public function getAlias() {
                return 'admin';
            }

            public function load(array $configs, ContainerBuilder $container) {
                $loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/Resources/config'));
                $loader->load('services.php');
            }
        };
    }
}

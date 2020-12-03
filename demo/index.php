<?php

use Krak\Admin\Templates\Crud\CrudCreatePage;
use Krak\Admin\Templates\HomePage;
use League\Plates\Bridge\Symfony\PlatesBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

if (preg_match('/\.(?:css)$/', $_SERVER['REQUEST_URI'])) {
    return false; // serve as is
}

require_once __DIR__ . '/../vendor/autoload.php';

$kernel = new class('dev', true) extends Kernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable {
        yield new FrameworkBundle();
        yield new PlatesBundle();
    }

    public function configureRoutes(RoutingConfigurator $routes) {
        $routes
        ->add('home', '/')
            ->controller([$this, 'homePage'])
        ->add('crud_create', '/crud/create')
            ->controller([$this, 'crudCreatePage'])
        ;
    }

    public function configureContainer(ContainerConfigurator $c) {

    }

    public function homePage() {
        return new HomePage();
    }

    public function crudCreatePage() {
        return new CrudCreatePage([]);
    }
};

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);

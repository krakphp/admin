<?php

use Krak\Admin\Form\Field;
use Krak\Admin\Form\FieldType;
use Krak\Admin\Form\Form;
use Krak\Admin\Templates\Crud\CrudCreatePage;
use Krak\Admin\Templates\Crud\CrudListPage;
use Krak\Admin\Templates\HomePage;
use League\Plates\Bridge\Symfony\PlatesBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpFoundation\Request;
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
            ->methods(['GET', 'POST'])
        ->add('crud_list', '/crud')
            ->controller([$this, 'crudListPage'])
        ;
    }

    public function configureContainer(ContainerConfigurator $c) {
        $c->extension('framework', [
            'session' => ['enabled' => true]
        ]);
    }

    public function homePage() {
        return new HomePage();
    }

    public function crudCreatePage(Request $req) {
        if ($req->isMethod('POST')) {
            $req->getSession()->getFlashBag()->add('success', 'Saved all fields 1!');
            $req->getSession()->getFlashBag()->add('success', 'Saved all fields 2!');
            $req->getSession()->getFlashBag()->add('warning', 'But there could be an error.');
            $req->getSession()->getFlashBag()->add('error', 'But field 4 had a major issue.');
        }

        return new CrudCreatePage(new Form('Order', [
            Field::new('field1', FieldType::string())
                ->withDisplayName('Field 1')
                ->optional(),
            Field::new('field2', FieldType::string())
                ->withDisplayName('Field 2')
                ->optional(),
            Field::new('field3', FieldType::string())
                ->withDisplayName('Field 3')
                ->required(),
            Field::new('field4', FieldType::string())
                ->withDisplayName('Field 4')
                ->optional(),
            Field::new('field5', FieldType::string())
                ->withDisplayName('Field 5')
                ->required(),
        ]));
    }

    public function crudListPage() {
        return new CrudListPage();
    }
};

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);

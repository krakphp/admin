<?php

use Krak\Admin\Form\Field;
use Krak\Admin\Form\FieldType;
use Krak\Admin\Form\Form;
use Krak\Admin\Templates\Crud\CrudCreatePage;
use Krak\Admin\Templates\Crud\CrudListPage;
use Krak\Admin\Templates\HomePage;
use Krak\Admin\Templates\Layout\OneColumnLayout;
use Krak\Admin\Templates\Typography;
use League\Plates\Bridge\Symfony\PlatesBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use function League\Plates\attrs;
use function League\Plates\p;

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
        ->add('size_scales', '/size-scales')
            ->controller([$this, 'sizeScalesEdit'])

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

    public function dynamicFormEdit() {
        return function() {
            echo p((new OneColumnLayout(function() {
            ?>  <?=p(Typography::PageTitle('Sizes | Create'))?>
                <form class="grid grid-cols-4 gap-4" method="POST">
                    <div x-data="{ sizes: [] }">
                      <div class="space-x-1 mb-2">
                        <?=p(Typography::Button('Add Size', 'info', ['type' => 'button', '@click' => "sizes.push('')"]))?>
                        <?=p(Typography::Button('Remove Size', 'info', ['type' => 'button', '@click' => 'sizes.shift()']))?>
                      </div>
                      <template x-for="(size, index, collection) in sizes" :key="index">
                          <?=p(self::FormElement(function() {
                              ?>  <?=p(self::Label('Size', ['x-text' => "'Size ' + (index + 1)"]))?>
                                  <?=p(self::TextInput('size[]', null, ['x-model' => 'collection[index]']))?> <?php
                          }))?>
                      </template>
                    </div>
                </form>
                <?php
            }))
                ->title('Size Scales'));
        };
    }

    private static function FormElement($children, $attrs = []) {
        return function() use ($children, $attrs) {
        ?>  <div <?= attrs(['class' => 'align-middle col-span-2'], $attrs)?>>
              <label class="flex-col items-center w-full space-y-1">
                  <?=p($children)?>
              </label>
            </div> <?php
        };
    }

    private static function Label(string $title, array $attrs = []) {
        return function() use ($title, $attrs) {
            ?> <span class="whitespace-nowrap inline-block text-gray-900 font-medium" <?=attrs($attrs)?>><?=$title?></span> <?php
        };
    }

    private static function TextInput(string $name, ?string $value = null, array $attrs = []) {
        return function() use ($name, $value, $attrs) {
            ?> <input <?=attrs($attrs)?> type="text" name="<?=$name?>" value="<?=$value?>" class="focus:ring-pink-500 focus:border-pink-500 block w-full sm:text-sm border-gray-300 rounded-md"/> <?php
        };
    }
};

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);

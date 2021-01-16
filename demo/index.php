<?php

use Krak\Admin\Form\Field;
use Krak\Admin\Form\FieldType;
use Krak\Admin\Form\Form;
use Krak\Admin\Templates\Crud\CrudCreatePage;
use Krak\Admin\Templates\Crud\CrudListPage;
use Krak\Admin\Templates\HomePage;
use Krak\Admin\Templates\Layout\OneColumnLayout;
use Krak\Admin\Templates\Typography;
use League\Plates\Bridge\AlpineJs\AlpineJs;
use League\Plates\Bridge\Symfony\PlatesBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use function League\Plates\attrs;
use function League\Plates\e;
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
            ->controller([$this, 'modalTestPage'])

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
            echo (new OneColumnLayout(function() {
            ?>  <?=Typography::PageTitle('Sizes | Create')?>
                <form class="grid grid-cols-4 gap-4" method="POST">
                  <?=AlpineJs::Component(['sizes' => ['a', 'b', 'c']], function() {
                  ?>  <div class="space-x-1 mb-2">
                        <?=Typography::Button('Add Size', 'info', ['type' => 'button', '@click' => "sizes.push('')"])?>
                        <?=Typography::Button('Remove Size', 'info', ['type' => 'button', '@click' => 'sizes.shift()'])?>
                      </div>
                      <template x-for="(size, index, collection) in sizes" :key="index">
                        <?=self::FormElement(function() {
                        ?>  <?=self::Label('Size', ['x-text' => "'Size ' + (index + 1)"])?>
                            <?=self::TextInput('size[]', null, ['x-model' => 'collection[index]'])?> <?php
                        })?>
                      </template> <?php
                  })?>
                </form>
                <?php
            }))
                ->title('Size Scales');
        };
    }

    public function modalTestPage() {
        $Modal = function(string $name) {
            return p(function() use ($name) {
              ?><?=AlpineJs::Component(['value' => 1], function() use ($name) {
                    ?> <?=$name?>: <span x-text="value"></span> <?php
              }, [
                '@admin:modal:open:'.$name.'.window' => 'value += 1',
                '@admin:modal:close:'.$name.'.window' => 'value -= 1',
              ])?><?php
            });
        };

        return function() use ($Modal) {
            echo (new OneColumnLayout(function() use ($Modal) {
            ?>  <?=Typography::PageTitle('Modal Test')?>
                <?=self::Modal('a', function() {
                  ?> Modal A <?php
                })?>
                <?=self::Modal('b', function() {
                  ?> Modal B <?php
                })?>
                <?=AlpineJs::Component([], function() {
                    ?> <?=Typography::Button('Increment A Value', 'info', ['@click' => "\$dispatch('admin:modal:open:a')"])?> <?php
                    ?> <?=Typography::Button('Increment B Value', 'info', ['@click' => "\$dispatch('admin:modal:open:b')"])?> <?php
                })?>
            <?php
            }))->title('Modal Test');
        };
    }

    private static function Modal(string $name, $children) {
        return p(function() use ($name, $children) {
        ?>
          <div class="fixed z-10 inset-0 overflow-y-auto" x-data="{ show: false }" :class="{ hidden: !show }" @admin:modal:open:<?=$name?>.window="show = true" @keydown.escape.window="show = false">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
              <!--
                Background overlay, show/hide based on modal state.

                Entering: "ease-out duration-300"
                  From: "opacity-0"
                  To: "opacity-100"
                Leaving: "ease-in duration-200"
                  From: "opacity-100"
                  To: "opacity-0"
              -->
              <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
              </div>

              <!-- This element is to trick the browser into centering the modal contents. -->
              <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
              <!--
                Modal panel, show/hide based on modal state.

                Entering: "ease-out duration-300"
                  From: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                  To: "opacity-100 translate-y-0 sm:scale-100"
                Leaving: "ease-in duration-200"
                  From: "opacity-100 translate-y-0 sm:scale-100"
                  To: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
              -->
              <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                  <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                      <!-- Heroicon name: exclamation -->
                      <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                      </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                      <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                        <?=p($children)?>
                      </h3>
                      <div class="mt-2">
                        <p class="text-sm text-gray-500">
                          Are you sure you want to deactivate your account? All of your data will be permanently removed. This action cannot be undone.
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                  <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Deactivate
                  </button>
                  <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" @click="show = false">
                    Cancel
                  </button>
                </div>
              </div>
            </div>
          </div>
        <?php
        });
    }

    private static function FormElement($children, $attrs = []) {
        return p(function() use ($children, $attrs) {
        ?>  <div <?= attrs(['class' => 'align-middle col-span-2'], $attrs)?>>
              <label class="flex-col items-center w-full space-y-1">
                  <?=p($children)?>
              </label>
            </div> <?php
        });
    }

    private static function Label(string $title, array $attrs = []) {
        return p(function() use ($title, $attrs) {
            ?> <span class="whitespace-nowrap inline-block text-gray-900 font-medium" <?=attrs($attrs)?>><?=$title?></span> <?php
        });
    }

    private static function TextInput(string $name, ?string $value = null, array $attrs = []) {
        return p(function() use ($name, $value, $attrs) {
            ?> <input <?=attrs($attrs)?> type="text" name="<?=$name?>" value="<?=$value?>" class="focus:ring-pink-500 focus:border-pink-500 block w-full sm:text-sm border-gray-300 rounded-md"/> <?php
        });
    }
};

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);

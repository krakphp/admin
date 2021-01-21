<?php

namespace Demo\App;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Krak\Admin\Bridge\Symfony\AdminBundle;
use Krak\Admin\Form\{Field, FieldType, Form};
use Krak\Admin\Templates\{Crud\CrudCreatePage, Crud\CrudListPage, HomePage, Layout\OneColumnLayout, Modal, Typography};
use League\Plates\Bridge\AlpineJs\AlpineJs;
use League\Plates\Bridge\Symfony\PlatesBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use function League\Plates\{attrs, p};

final class AppKernel extends Kernel
{
    use MicroKernelTrait;

    public function getProjectDir() {
        return __DIR__ . '/..';
    }

    public function registerBundles(): iterable {
        yield new FrameworkBundle();
        yield new DoctrineBundle();
        yield new PlatesBundle();
        yield new AdminBundle();
    }

    public function configureRoutes(RoutingConfigurator $routes) {
        $routes
          ->import(__DIR__ . '/Catalog/Infra/Symfony/routes.php')
            ->prefix('/admin')
        ;

        $routes
          ->add('home', '/')
              ->controller([$this, 'homePage'])
          ->add('crud_create', '/crud/create')
              ->controller([$this, 'crudCreatePage'])
              ->methods(['GET', 'POST'])
          ->add('crud_list', '/crud')
              ->controller([$this, 'crudListPage'])
          ->add('size_scales', '/size-scales')
              ->controller([$this, 'dynamicFormEdit'])

        ;
    }

    public function configureContainer(ContainerConfigurator $c) {
        $c->import(__DIR__ . '/Catalog/Infra/Symfony/services.php');
        $c->extension('framework', [
            'session' => ['enabled' => true]
        ]);

        $c->extension('doctrine', [
            'dbal' => [
                'url' => 'sqlite:///%kernel.project_dir%/var/data.db',
                'types' => [
                    'size_scale_status' => Catalog\Infra\Doctrine\SizeScaleStatusType::class,
                ]
            ],
            'orm' => [
                'auto_generate_proxy_classes' => true,
                'naming_strategy' => 'doctrine.orm.naming_strategy.underscore_number_aware',
                'auto_mapping' => true,
                'mappings' => [
                    'Catalog' => [
                        'is_bundle' => false,
                        'type' => 'xml',
                        'dir' => '%kernel.project_dir%/src/Catalog/Infra/Persistence/mapping',
                        'prefix' => 'Demo\App\Catalog\Domain'
                    ]
                ]
            ]
        ]);

        $c->services()->defaults()->autowire()->autoconfigure()->private()->set(PlaygroundCommand::class);
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
       return function() {
            \League\Plates\context(\League\Plates\Portal::class, 'modals')
                ->append(new Modal('a', [Modal::Body([Modal::Title('Modal A'), Modal::Copy('This is the modal content.')]), Modal::Footer()]))
            ;

            echo (new OneColumnLayout(function() {
            ?>  <?=Typography::PageTitle('Modal Test')?>
                <?=AlpineJs::Component([], [
                    Typography::Button('Increment A Value', 'info', ['@click' => "\$dispatch('admin:modal:open:a')"])
                ])?>
            <?php
            }))->title('Modal Test');
        };
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
}

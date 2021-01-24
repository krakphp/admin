<?php

namespace Krak\Admin\Templates\Layout;

use League\Plates\Component;
use League\Plates\ComponentContext;
use League\Plates\Portal;
use function League\Plates\attrs;
use function League\Plates\Bridge\Symfony\path;
use function League\Plates\context;
use function League\Plates\p;

final class OneColumnLayout extends Component
{
    private $title = 'Admin';
    /** @var string[] */
    private $styleSheets = [];
    private $children;

    public function __construct($children) {
        $this->children = $children;
    }

    public function __invoke(): void {
      $children = (string) p($this->children); // ensure that any of the global buffers get filled.
      $nav = $this->nav();
    ?>  <!DOCTYPE html>
        <html lang="en" class="h-full">
            <head>
                <title><?=p($this->title)?></title>
                <link rel="stylesheet" type="text/css" href="/css/admin.css" />
                <?php foreach ($this->styleSheets as $styleSheet): ?>
                <link rel="stylesheet" type="text/css" href="<?=$styleSheet?>" />
                <?php endforeach; ?>
                <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.3/dist/alpine.min.js" defer></script>
            </head>
            <body class="flex flex-col sm:flex-row h-full items-stretch">
              <?=$nav?>
              <div class="bg-gray-50 h-full flex-grow h-96">
                <div class="container mx-auto py-6 px-8 text-gray-800">
                  <?=$children?>
                </div>
              </div>
              <?=context(Portal::class, 'modals')?>
            </body>
        </html><?php
    }

    public function title(string $title): self {
        $this->title = $title; return $this;
    }

    /** @param string[] $styleSheets */
    public function styleSheets(array $styleSheets): self {
        $this->styleSheets = $styleSheets; return $this;
    }

    private function nav(): string {
        return (string) new Nav([
            Nav::Item('Home', path('home')),
            Nav::Item('Crud', path('crud_list')),
            Nav::Item('Crud Create', path('crud_create')),
            Nav::Item('Dynamic Form', path('size_scales')),
            Nav::Item('Size Scales', path('catalog_size_scale_admin_list')),
        ]);
    }
}

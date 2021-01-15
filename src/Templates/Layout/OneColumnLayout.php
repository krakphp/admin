<?php

namespace Krak\Admin\Templates\Layout;

use League\Plates\Component;
use function League\Plates\attrs;
use function League\Plates\Bridge\Symfony\path;
use function League\Plates\p;

final class OneColumnLayout extends Component
{
    private $title = 'Admin';
    /** @var string[] */
    private $styleSheets = [];
    private $children;

    public function __construct($children) {
        $this->children = p($children);
    }

    public function __invoke(): void {
    ?>  <!DOCTYPE html>
        <html lang="en" class="h-full">
            <head>
                <title><?=$this->title?></title>
                <link rel="stylesheet" type="text/css" href="/css/admin.css" />
                <?php foreach ($this->styleSheets as $styleSheet): ?>
                <link rel="stylesheet" type="text/css" href="<?=$styleSheet?>" />
                <?php endforeach; ?>
                <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.3/dist/alpine.min.js" defer></script>
            </head>
            <body class="flex flex-col sm:flex-row h-full items-stretch">
              <?=new Nav([
                Nav::Item('Home', path('home')),
                Nav::Item('Crud', path('crud_list')),
                Nav::Item('Crud Create', path('crud_create')),
                Nav::Item('Dynamic Form', path('size_scales'))
              ])?>
              <div class="bg-gray-50 h-full flex-grow h-96">
                <div class="container mx-auto py-6 px-8 space-y-2 text-gray-800">
                  <?=$this->children?>
                </div>
              </div>
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
}

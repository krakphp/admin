<?php

namespace Krak\Admin\Templates\Layout;

use function League\Plates\attrs;
use function League\Plates\Bridge\Symfony\path;
use function League\Plates\p;

final class OneColumnLayout
{
    private $title = 'Admin';
    /** @var string[] */
    private $styleSheets = [];
    private $children;

    public function __construct(callable $children) {
        $this->children = $children;
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
              <?=p(new Nav([
                Nav::Item('Home', path('home')),
                Nav::Item('Crud', path('crud_list')),
                Nav::Item('Crud Create', path('crud_create')),
              ]))?>
              <div class="bg-gray-50 h-full flex-grow h-96">
                <div class="container py-6 px-8 space-y-2 text-gray-800">
                  <?=p($this->children)?>
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

    private static function Button(string $title, string $type = 'info', array $attrs = []) {
        return function() use ($title, $type, $attrs) {
          switch ($type) {
          case 'success': $classes = 'bg-green-400 hover:bg-green-500'; break;
          case 'info':
          default: $classes = 'bg-blue-500 hover:bg-blue-600'; break;
          }

          ?> <button class="rounded-md hover:underline text-sm <?=$classes?> text-white px-4 py-2" <?=attrs($attrs)?>><?=$title?></button> <?php
        };
    }

    private static function TableWrapper($children) {
        return function() use ($children) {
        ?>  <div class="flex flex-col">
              <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                  <?=p($children)?>
                </div>
              </div>
            </div> <?php
        };
    }

    private static function Th($children) {
        return function() use ($children) {
        ?>  <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-pink-500 uppercase tracking-wider">
              <?=p($children)?>
            </th> <?php
        };
    }

    private static function Td($children) {
        return function() use ($children) {
        ?>  <td class="px-6 py-4 whitespace-nowrap">
              <?=p($children)?>
            </td> <?php
        };
    }
}

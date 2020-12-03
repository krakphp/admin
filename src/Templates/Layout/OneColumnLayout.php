<?php

namespace Krak\Admin\Templates\Layout;

use function League\Plates\attrs;
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
              <?=p(new Nav(array_map(function(int $i) {
                return Nav::Item('Home: ' . $i, $i === 2);
              }, range(1, 10))))?>
              <div class="bg-gray-50 h-full flex-grow h-96">
                <div class="container py-6 px-8 space-y-2 text-gray-800">
                  <h1 class="font-medium text-2xl">Order Listing</h1>
                  <div class="flex justify-between">
                    <div></div>
                    <div class="flex space-x-2">
                        <?=p(self::Button('Add', 'success'))?>
                        <?=p(self::Button('Export'))?>
                    </div>
                  </div>

                  <?=p(self::TableWrapper(function() {
                  ?>  <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-pink-200">
                          <thead>
                            <tr>
                              <?=p(self::Th('Name'))?>
                              <?=p(self::Th('Title'))?>
                              <?=p(self::Th('Status'))?>
                              <?=p(self::Th('Role'))?>
                              <?=p(self::Th(function() {
                                ?> <span class="sr-only">Edit</span> <?php
                              }))?>
                            </tr>
                          </thead>
                          <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                              <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                  <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=4&amp;w=256&amp;h=256&amp;q=60" alt="">
                                  </div>
                                  <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                      Jane Cooper
                                    </div>
                                    <div class="text-sm text-gray-500">
                                      jane.cooper@example.com
                                    </div>
                                  </div>
                                </div>
                              </td>
                              <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">Regional Paradigm Technician</div>
                                <div class="text-sm text-gray-500">Optimization</div>
                              </td>
                              <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                  Active
                                </span>
                              </td>
                              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                Admin
                              </td>
                              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <a href="#" class="text-blue-500 hover:text-blue-600 hover:underline">View</a>
                                <a href="#" class="text-blue-500 hover:text-blue-600 hover:underline">Edit</a>
                              </td>
                            </tr>
                          <!-- More rows... -->
                          </tbody>
                        </table>
                    </div> <?php
                  }))?>
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

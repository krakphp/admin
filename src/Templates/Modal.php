<?php

namespace Krak\Admin\Templates;

use League\Plates\Component;
use function League\Plates\p;

final class Modal extends Component
{
    private $name;
    private $children;

    public function __construct(string $name, $children) {
        $this->name = $name;
        $this->children = p($children);
    }

    public function __invoke(): void {
    ?>
      <div class="fixed z-10 inset-0 overflow-y-auto" x-data="{ show: false }" :class="{ hidden: !show }" @admin:modal:open:<?=$this->name?>.window="show = true" @keydown.escape.window="show = false">
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
                <?=$this->children?>
              </div>
          </div>
      </div>
    <?php
    }

    public static function Title($children) {
        return p(function() use ($children) {
        ?>
          <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
              <?=p($children)?>
          </h3>
        <?php
        });
    }

    public static function Copy($children) {
        return p(function() use ($children) {
        ?>
          <div class="mt-2">
            <p class="text-sm text-gray-500">
              <?=p($children)?>
            </p>
          </div>
        <?php
        });
    }

    public static function Body($children) {
        return p(function() use ($children) {
        ?>
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
              <?=p($children)?>
            </div>
          </div>
        <?php
        });
    }

    public static function Footer() {
        return p(function() {
        ?>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
              Deactivate
            </button>
            <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" @click="show = false">
              Cancel
            </button>
          </div>
        <?php
        });
    }
}

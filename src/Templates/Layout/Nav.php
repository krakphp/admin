<?php

namespace Krak\Admin\Templates\Layout;

use League\Plates\Component;
use function League\Plates\p;

final class Nav extends Component
{
    private $navItems;
    private $title = 'Integrations Admin';

    public function __construct(array $navItems = []) {
        $this->navItems = $navItems;
    }
    
    public function __invoke(): void {
    ?>  <div>
          <?=$this->DesktopNav()?>
          <?=$this->MobileNav()?>
        </div>
        <script type="text/javascript">
          function AdminNavComponent(type, defaultCollapsed) {
            return {
              collapsed: defaultCollapsed,
              init: function() {
                const localCollapsedState = window.localStorage.getItem('admin_nav_collapsed_' + type);
                if (localCollapsedState === 'collapsed') {
                  this.collapsed = true;
                } else if (localCollapsedState === 'open') {
                  this.collapsed = false;
                }
              },
              collapse: function() {
                this.collapsed = true;
                window.localStorage.setItem('admin_nav_collapsed_' + type, 'collapsed');
              },
              open: function() {
                this.collapsed = false;
                window.localStorage.setItem('admin_nav_collapsed_' + type, 'open');
              }
            };
          }
        </script> <?php
    }

    private function DesktopNav() {
        return p(function() {
        ?>  <div class="flex-col h-full bg-pink-500 divide-y divide-pink-200 hidden sm:flex" x-data="AdminNavComponent('desktop', false)" x-init="init()">
              <div class="text-pink-100">
                <h1 class="px-4 pt-4 text-2xl font-light uppercase tracking-wide" :class="{ hidden: collapsed }"><?=$this->title?></h1>
                <div
                    class="text-pink-200 hover:text-pink-50 underline pl-4 mb-2 text-xs cursor-pointer"
                    :class="{ hidden: collapsed }"
                    @click="collapse()" >Collapse Sidebar</div>
                <div
                    :class="{ hidden: !collapsed }"
                    @click="open()"><?=self::MenuIcon()?></div>
              </div>

              <div class="flex-grow p-2 overflow-auto" :class="{ hidden: collapsed }">
                <ul>
                  <?php foreach ($this->navItems as $navItem): ?>
                    <?=$navItem?>
                  <?php endforeach; ?>
                </ul>
              </div>

              <div class="flex-shrink-0 flex items-center p-4" :class="{ hidden: collapsed }">
                <div class="h-12 w-12 rounded-full bg-blue-200"></div>
                <div class="ml-3 text-pink-100">
                  <div>Bob Whitely</div>
                </div>
              </div>
            </div> <?php
        });
    }
    
    private function MobileNav() {
        return p(function() {
        ?>  <div class="sm:hidden flex flex-col h-full bg-pink-500 divide-y divide-pink-200" x-data="AdminNavComponent('mobile', true)" x-init="init()">
              <div class="text-pink-100">
                <div :class="{ hidden: !collapsed }" @click="open()"><?=self::MenuIcon()?></div>
                <div :class="{ hidden: collapsed }" @click="collapse()"><?=self::CloseIcon()?></div>
              </div>

              <h1 class="text-pink-100 p-4 text-2xl font-light uppercase tracking-wide" :class="{ hidden: collapsed }"><?=$this->title?></h1>

              <div class="flex-grow p-2 overflow-auto" :class="{ hidden: collapsed }">
                <ul>
                    <?php foreach ($this->navItems as $navItem): ?>
                        <?=$navItem?>
                    <?php endforeach; ?>
                </ul>
              </div>
              <div class="flex-shrink-0 flex items-center p-4" :class="{ hidden: collapsed }">
                <div class="h-12 w-12 rounded-full bg-blue-200"></div>
                <div class="ml-3 text-pink-100">
                  <div>Bob Whitely</div>
                </div>
              </div>
            </div> <?php
        });
    }

    public static function Item(string $title, string $link, bool $selected = false) {
        return p(function() use ($title, $link, $selected) {
        ?>  <li class="<?=$selected ? 'text-pink-50 bg-pink-600' : 'text-pink-200'?> hover:text-pink-50 hover:bg-pink-600 cursor-pointer rounded-md p-2">
              <a class="block" href="<?=$link?>"><?=$title?></a>
            </li> <?php
        });
    }

    private static function MenuIcon() {
        return p(function() {
        ?>  <svg class="m-2 cursor-pointer w-10 hover:text-pink-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg> <?php
        });
    }

    private static function CloseIcon() {
        return p(function() {
        ?>  <svg class="m-2 cursor-pointer w-10 hover:text-pink-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg> <?php
        });
    }
}

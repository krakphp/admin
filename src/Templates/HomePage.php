<?php

namespace Krak\Admin\Templates;

use Krak\Admin\Templates\Layout\OneColumnLayout;
use League\Plates\Component;
use function League\Plates\Bridge\Symfony\url;

final class HomePage extends Component
{
    public function __invoke(): void {
        echo (new OneColumnLayout(function() {
        ?>  <h1 class="font-semibold text-2xl">This is the Home page</h1>
            <ul class="list-disc pl-6">
              <li>
                <?=Typography::textLink('Create Entity', url('crud_create'))?>
              </li>
            </ul>
            <?php
        }))
            ->title('Home')
            ->styleSheets([])
        ;
    }
}

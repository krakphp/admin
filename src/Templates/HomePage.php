<?php

namespace Krak\Admin\Templates;

use Krak\Admin\Templates\Layout\OneColumnLayout;
use function League\Plates\Bridge\Symfony\url;
use function League\Plates\p;

final class HomePage
{
    public function __invoke(): void {
        echo p((new OneColumnLayout(function() {
        ?>  <h1 class="font-semibold text-2xl">This is the Home page</h1>
            <ul class="list-disc pl-6">
              <li>
                <?=p(Typography::textLink('Create Entity', url('crud_create')))?>
              </li>
            </ul>
            <?php
        }))
            ->title('Home')
            ->styleSheets([])
        );
    }
}

<?php

namespace Demo\App\Catalog\UI\Component\SizeScale;

use Krak\Admin\Templates\Layout\OneColumnLayout;
use League\Plates\Extension\AlpineJs\AlpineJs;
use League\Plates\Component;
use League\Plates\Portal;
use function Krak\Admin\Templates\Form\FormElement;
use function Krak\Admin\Templates\Form\Label;
use function Krak\Admin\Templates\Form\TagsInput;
use function Krak\Admin\Templates\Form\TextInput;
use function Krak\Admin\Templates\Typography\Button;
use function Krak\Admin\Templates\Typography\ButtonLink;
use function Krak\Admin\Templates\Typography\Card;
use function Krak\Admin\Templates\Typography\DefinitionList;
use function Krak\Admin\Templates\Typography\DefinitionListItem;
use function Krak\Admin\Templates\Typography\PageTitle;
use function Krak\Admin\Templates\Typography\TextLink;
use function League\Plates\Extension\Symfony\path;
use function League\Plates\escape;
use function League\Plates\h;
use function League\Plates\p;

final class SizeScaleCreatePage extends Component
{
    public function __invoke(): void {
        $title = 'Size Scales | Create';
        echo (new OneColumnLayout(function() use ($title) {
        ?>
            <?=PageTitle($title)?>

            <?=Card(function() {
            ?>
                <form class="p-4 space-y-4" method="post">
                    <?=p([
                        FormElement([
                            Label('Name'),
                            TextInput('name')
                        ]),
                        FormElement([
                            Label('Sizes'),
                            TagsInput('sizes[]')
                        ]),
                    ])?>
                    <div class="space-x-2">
                        <?=p([
                            Button('Create', 'success', ['type' => 'submit']),
                            TextLink('Show All', path('catalog_size_scale_admin_list')),
                        ])?>
                    </div>
                </form>
            <?php
            }, ['class' => 'max-w-lg'])?>

        <?php
        }))->title($title);
    }
}

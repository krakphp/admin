<?php

namespace Demo\App\Catalog\UI\Component\SizeScale;

use Krak\Admin\Templates\Layout\OneColumnLayout;
use League\Plates\Component;
use function Krak\Admin\Templates\Form\FormElement;
use function Krak\Admin\Templates\Form\Label;
use function Krak\Admin\Templates\Form\TextInput;
use function Krak\Admin\Templates\Typography\Button;
use function Krak\Admin\Templates\Typography\ButtonLink;
use function Krak\Admin\Templates\Typography\Card;
use function Krak\Admin\Templates\Typography\DefinitionList;
use function Krak\Admin\Templates\Typography\DefinitionListItem;
use function Krak\Admin\Templates\Typography\PageTitle;
use function Krak\Admin\Templates\Typography\TextLink;
use function League\Plates\Bridge\Symfony\path;
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
                    <?=FormElement([
                        Label('Name'),
                        TextInput('name')
                    ])?>
                    <div class="space-x-2">
                        <?=p([
                            Button('Create', 'success', ['type' => 'submit']),
                            TextLink('Back', path('catalog_size_scale_admin_list')),
                        ])?>
                    </div>
                </form>
            <?php
            }, ['class' => 'max-w-lg'])?>

        <?php
        }))->title($title);
    }
}

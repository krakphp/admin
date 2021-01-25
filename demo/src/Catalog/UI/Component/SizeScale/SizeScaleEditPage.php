<?php

namespace Demo\App\Catalog\UI\Component\SizeScale;

use Demo\App\Catalog\Domain\SizeScale;
use Krak\Admin\Templates\Layout\OneColumnLayout;
use League\Plates\Component;
use function Krak\Admin\Templates\Form\FormElement;
use function Krak\Admin\Templates\Form\Label;
use function Krak\Admin\Templates\Form\TextInput;
use function Krak\Admin\Templates\Typography\Button;
use function Krak\Admin\Templates\Typography\Card;
use function Krak\Admin\Templates\Typography\TextLink;
use function League\Plates\Bridge\Symfony\path;
use function League\Plates\p;

final class SizeScaleEditPage extends Component
{
    private $sizeScale;

    public function __construct(SizeScale $sizeScale) {
        $this->sizeScale = $sizeScale;
    }

    public function __invoke(): void {
        echo (new OneColumnLayout(function() {
        ?>
            <?=Card(function() {
            ?>
                <form class="p-4 space-y-4" method="post">
                    <?=FormElement([
                        Label('Name'),
                        TextInput('name', $this->sizeScale->name())
                    ])?>
                    <div class="space-x-2">
                        <?=p([
                            Button('Save', 'success', ['type' => 'submit']),
                            TextLink('Show All', path('catalog_size_scale_admin_list')),
                        ])?>
                    </div>
                </form>
            <?php
            }, ['class' => 'max-w-lg'])?>

        <?php
        }))->titleAndPageTitle('Size Scales | ' . $this->sizeScale->name());
    }
}

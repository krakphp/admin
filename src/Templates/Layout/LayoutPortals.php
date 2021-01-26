<?php

namespace Krak\Admin\Templates\Layout;

use League\Plates\Portal;

final class LayoutPortals
{
    /** @var Portal */
    private $modals;
    /** @var Portal */
    private $js;

    public function __construct() {
        $this->modals = new Portal();
        $this->js = new Portal();
    }

    public function modals(): Portal {
        return $this->modals;
    }

    public function js(): Portal {
        return $this->js;
    }
}

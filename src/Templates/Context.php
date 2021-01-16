<?php

namespace Krak\Admin\Templates;

use Krak\Admin\Form\ConvertFieldToHtmlElement;
use League\Plates\Portal;

final class Context
{
    /** @var Portal */
    public $modalPortal;

    public function __construct() {
        $this->modalPortal = new Portal();
    }
}

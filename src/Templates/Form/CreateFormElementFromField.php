<?php

namespace Krak\Admin\Templates\Form;

use Krak\Admin\Form\Field;

interface CreateFormElementFromField
{
    public function __invoke(Field $field): callable;
}

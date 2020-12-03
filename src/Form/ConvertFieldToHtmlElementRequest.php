<?php

namespace Krak\Admin\Form;

final class ConvertFieldToHtmlElementRequest
{
    private $field;

    public function __construct(Field $field) {
        $this->field = $field;
    }

    public function field(): Field {
        return $this->field;
    }
}

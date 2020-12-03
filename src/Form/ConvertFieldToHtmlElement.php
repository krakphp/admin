<?php

namespace Krak\Admin\Form;

interface ConvertFieldToHtmlElement
{
    public function __invoke(ConvertFieldToHtmlElementRequest $req): HtmlFormElement;
}

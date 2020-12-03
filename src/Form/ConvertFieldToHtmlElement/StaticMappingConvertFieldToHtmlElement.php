<?php

namespace Krak\Admin\Form\ConvertFieldToHtmlElement;

use Krak\Admin\Form\ConvertFieldToHtmlElement;
use Krak\Admin\Form\ConvertFieldToHtmlElementRequest;
use Krak\Admin\Form\HtmlFormElement;

final class StaticMappingConvertFieldToHtmlElement implements ConvertFieldToHtmlElement
{
    public function __invoke(ConvertFieldToHtmlElementRequest $req): HtmlFormElement {
        return HtmlFormElement::text($req->field()->name(), $req->field()->name(), (string) $req->field()->value());
    }
}

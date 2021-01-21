<?php

namespace Krak\Admin\Provider;

use Krak\Admin\Form\ConvertFieldToHtmlElement;
use League\Plates\ComponentContext;
use League\Plates\Portal;
use League\Plates\ProvideComponentContext;

final class ProvideAdminContext implements ProvideComponentContext
{
    private $convertFieldToHtmlElement;

    public function __construct(ConvertFieldToHtmlElement $convertFieldToHtmlElement) {
        $this->convertFieldToHtmlElement = $convertFieldToHtmlElement;
    }

    public function __invoke(ComponentContext $context): void {
        $context->addStatic(ConvertFieldToHtmlElement::class, $this->convertFieldToHtmlElement);
        $context->add(Portal::class, function() { return new Portal(); }, 'modals');
    }
}

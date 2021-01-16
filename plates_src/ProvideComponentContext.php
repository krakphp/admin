<?php

namespace League\Plates;

// add context onto the component context instance
interface ProvideComponentContext
{
    public function __invoke(ComponentContext $context): void;
}

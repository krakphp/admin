<?php

namespace League\Plates\Extension\Symfony;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/** Copied mostly verbatim from Twig Symfony Bridge RoutingExtension */
final class RoutingFunctions
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator) {
        $this->urlGenerator = $urlGenerator;
    }

    public function path(string $name, array $parameters = [], bool $relative = false): string
    {
        return $this->urlGenerator->generate($name, $parameters, $relative ? UrlGeneratorInterface::RELATIVE_PATH : UrlGeneratorInterface::ABSOLUTE_PATH);
    }

    public function url(string $name, array $parameters = [], bool $schemeRelative = false): string
    {
        return $this->urlGenerator->generate($name, $parameters, $schemeRelative ? UrlGeneratorInterface::NETWORK_PATH : UrlGeneratorInterface::ABSOLUTE_URL);
    }
}

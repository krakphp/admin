<?php

use Symfony\Component\HttpFoundation\Request;

if (preg_match('/\.(?:css)$/', $_SERVER['REQUEST_URI'])) {
    return false; // serve as is
}

require_once __DIR__ . '/../vendor/autoload.php';

$kernel = new \Demo\App\AppKernel('dev', true);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);

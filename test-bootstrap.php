<?php

require_once __DIR__ . '/vendor/autoload.php';

function bootstrap(): void {
    $kernel = new \Demo\App\AppKernel('test', false);
    $kernel->boot();

    $console = new \Symfony\Bundle\FrameworkBundle\Console\Application($kernel);
    $console->setAutoExit(false);

    $console->run(new \Symfony\Component\Console\Input\ArrayInput([
        'command' => 'doctrine:schema:drop',
        '--force' => true,
    ]));
    $console->run(new \Symfony\Component\Console\Input\ArrayInput([
        'command' => 'doctrine:schema:update',
        '--force' => true
    ]));

    $kernel->shutdown();
}

bootstrap();

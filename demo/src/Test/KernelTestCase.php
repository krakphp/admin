<?php

namespace Demo\App\Test;

use Demo\App\AppKernel;

abstract class KernelTestCase extends \Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
{
    protected static function getKernelClass() {
        return AppKernel::class;
    }

    protected function setUp(): void {
        $this->bootKernel(['debug' => false]);
    }
}

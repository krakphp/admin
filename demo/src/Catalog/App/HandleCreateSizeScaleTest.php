<?php

namespace Demo\App\Catalog\App;

use Demo\App\AppKernel;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class HandleCreateSizeScaleTest extends KernelTestCase
{
    protected static function getKernelClass() {
        return AppKernel::class;
    }

    protected function setUp(): void {
        $this->bootKernel();
        $orm = self::$container->get(EntityManagerInterface::class);
//        self::$container->get(O)
    }

    /** @test */
    public function can_create_size_scale() {
//        $handler = new HandleCreateSizeScale(
//
//        );
    }
}

<?php

namespace Demo\App\Catalog\App;

use Demo\App\Catalog\Domain\CreateSizeScale;
use Demo\App\Catalog\Domain\SizeScale;
use Doctrine\ORM\EntityManagerInterface;

final class HandleCreateSizeScale
{
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function __invoke(CreateSizeScale $command): SizeScale {
        $sizeScale = SizeScale::create($command);
        $this->em->persist($sizeScale);
        $this->em->flush();
        return $sizeScale;
    }
}

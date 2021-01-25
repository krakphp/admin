<?php

namespace Demo\App\Catalog\App;

use Demo\App\Catalog\Domain\DeleteSizeScale;
use Demo\App\Catalog\Domain\SizeScaleRepository;

final class HandleDeleteSizeScale
{
    private $sizeScaleRepo;

    public function __construct(SizeScaleRepository $sizeScaleRepo) {
        $this->sizeScaleRepo = $sizeScaleRepo;
    }

    public function __invoke(DeleteSizeScale $command): void {
        $sizeScale = $this->sizeScaleRepo->get($command->sizeScaleId());
        $this->sizeScaleRepo->remove($sizeScale);
    }
}
